<?php

class Gbooksimport { 
	
	/* Google Service Object */
	public $serviceAPI;
	
	/* Number of successfuly inserted items */
	public $inserted = 0;
	
	
	public function __construct() { 
		
		include 'src/Google_Client.php';
		include 'src/contrib/Google_BooksService.php';
		
		$key = get_option('gbi_apikey');

		$client = new Google_Client();
				
		if(!empty($key)) { 
			$client->setDeveloperKey($key);
		}
	
		$client->setApplicationName('Google Books Importer');
		$this->serviceAPI = new Google_BooksService($client);
		
	}
	
	/* Import books function 
	 * 
	 * @param array of the books for import 
	 * @return int|false int on success ( number of imported books)
	 * */
	public function importBooks($ids) { 
		
		if(!is_array($ids)) 
			return;
		
		$fields = get_option('gbi_fields');
		
		foreach($ids as $id) { 
			
			$book = $this->formatBookdata($id);

			$post['post_title'] = is_string($book[$fields['post_title']]) ? $book[$fields['post_title']] : '';
			$post['post_content'] = is_string($book[$fields['post_content']]) ? $book[$fields['post_content']] : '';
			$post['post_excerpt'] = is_string($book[$fields['post_excerpt']]) ? $book[$fields['post_excerpt']] : '';
			$post['post_status'] = $fields['post_status'];
			$post['post_type'] = $fields['post_types'];
			$post['comment_status'] = $fields['comment_status'];

			$insert = wp_insert_post($post);
			if($insert) { 
				$this->inserted++;

				// category 
				if($fields['category'] !== '0') { 
					if($fields['post_types'] == 'post' && is_array($book[$fields['category']])) { 
						wp_set_object_terms($insert, $book[$fields['category']], 'category', true); 
					}
				}
				
				// custom fields
				if(isset($fields['customfields']) && !empty($fields['customfields'])) { 
					foreach($fields['customfields'] as $fi) { 
						update_post_meta($insert, $fi, $book[$fi]);
					}
				}
			}
		}
	
	}
	
	/* Search books 
	 * 
	 * @param string keyword for search
	 * @param array search parameters 
	 * @return array of results
	 * */
	public function searchBooks($query, $params = array()) { 
		
		try { 
			$result = $this->serviceAPI->volumes->listVolumes($query, $params);
			if($result['totalItems'] != 0) { 
				return $result['items'];
			} else { 
				return array();
			}
		} catch (Google_ServiceException $e) { 
			return 'Error while pulling search results';
		}
	
	}
	
	/* This function will pull volume data from object, filter values 
	 * and return needed values in array format. Returned values will include: 
	 * 
	 * id
	 * --- volume info ---
	 * title,subtitle,authors,publisher,publishedDate,description,pageCount,
	 * categories,averageRating,smallThumbnail,thumbnail,language,previewLink,
	 * infoLink
	 * --- sale info ---
	 * saleability,isEbook
	 * --- access info ---
	 * viewability,epub acsTokenLink,pdf acsTOkenLink,downloadLink,webReaderLink
	 * 
	 * @param int id of the book
	 * @return array book data
	 * */
	public function formatBookdata($id) { 
	
		$data = array();
	
		// raw data
		$book = $this->serviceAPI->volumes->get($id);
		
		$data['id'] = isset($book['id']) ? $book['id'] : '';
		// volume info
		$data['title'] = isset($book['volumeInfo']['title']) ? $book['volumeInfo']['title'] : '';
		$data['subtitle'] = isset($book['volumeInfo']['subtitle']) ? $book['volumeInfo']['subtitle'] : '';
		$data['authors'] = isset($book['volumeInfo']['authors']) ? implode(', ', $book['volumeInfo']['authors']) : '';
		$data['publisher'] = isset($book['volumeInfo']['publisher']) ? $book['volumeInfo']['publisher'] : '';
		$data['publishedDate'] = isset($book['volumeInfo']['publishedDate']) ? $book['volumeInfo']['publishedDate'] : '';
		$data['description'] = isset($book['volumeInfo']['description']) ? $book['volumeInfo']['description'] : '';
		$data['pageCount'] = isset($book['volumeInfo']['pageCount']) ? $book['volumeInfo']['pageCount'] : '';
		$data['categories'] = isset($book['volumeInfo']['categories']) ? $book['volumeInfo']['categories'] : array();
		$data['averageRating'] = isset($book['volumeInfo']['averageRating']) ? $book['volumeInfo']['averageRating'] : '';
		$data['smallThumbnail'] = isset($book['volumeInfo']['imageLinks']['smallThumbnail']) ? $book['volumeInfo']['imageLinks']['smallThumbnail'] : '';
		$data['mediumImage'] = isset($book['volumeInfo']['imageLinks']['medium']) ? $book['volumeInfo']['imageLinks']['medium'] : '';
		$data['largeImage'] = isset($book['volumeInfo']['imageLinks']['large']) ? $book['volumeInfo']['imageLinks']['large'] : '';
		$data['thumbnail'] = isset($book['volumeInfo']['imageLinks']['thumbnail']) ? $book['volumeInfo']['imageLinks']['thumbnail'] : '';
		$data['language'] = isset($book['volumeInfo']['language']) ? $book['volumeInfo']['language'] : '';
		$data['previewLink'] = isset($book['volumeInfo']['previewLink']) ? $book['volumeInfo']['previewLink'] : '';
		$data['infoLink'] = isset($book['volumeInfo']['infoLink']) ? $book['volumeInfo']['infoLink'] : '';
		
		// sale info
		$data['saleability'] = isset($book['saleInfo']['saleability']) ? $book['saleInfo']['saleability'] : '';
		$data['isEbook'] = isset($data['saleInfo']['isEbook']) ? 'yes' : 'no';
		
		//access info
		$data['viewability'] = isset($book['accessInfo']['viewability']) ? $book['accessInfo']['viewability'] : '';
		$data['epubacsTokenLink'] = isset($book['accessInfo']['epub']['acsTokenLink']) ? $book['accessInfo']['epub']['acsTokenLink'] : '';
		
		// if download link exists it will be passed here, otherwise pdf access token link is used
		if(isset($book['accessInfo']['pdf']['acsTokenLink'])) { 
			$data['pdfdownloadlink'] = $book['accessInfo']['pdf']['acsTokenLink'];
		} else if(isset($book['accessInfo']['pdf']['downloadLink'])) { 
			$data['pdfdownloadlink'] = $book['accessInfo']['pdf']['downloadLink'];
		} else { 
			$data['pdfdownloadlink'] = '';
		}
		
		$data['webReaderLink'] = isset($book['accessInfo']['webReaderLink']) ? $book['accessInfo']['webReaderLink'] : '';
		
		return $data;
		
	}
	
	
}

