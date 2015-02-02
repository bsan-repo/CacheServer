<?php

class AuthorsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return "Accessed authors controller!, ok"
		//return View::make('authors');
		/*
		return Response::json(array(
			'error' => false,
			'urls' => $urls->toArray()),
			200
        );*/
	}
	
	public function saveAuthorData(){
		//return View::make('authors');
		$data = Input::json();
		
		return Response::json(array(
			'error' => 'no errors',
			'urls' => 'urls value',
			'data' => $data->get('username')),
			200
        );
	}
	
	public function basicTest(){
		
		$data = Input::json();
		
		$credentials = array(
				'username' => $data->get('username'),
				'password' => $data->get('password'),
			);
		
		//die("Credentials: ".$data->get('username')."   ".$data->get('password'));
		
		if(Auth::attempt($credentials)){
		
		return Response::json(
			array(
				'error' => 'no errors',
				'data' => $data->get('username')
			),
			200
			);
        }else{
        	return Response::json(array(
				'error' => 'invalid credentials'),
				200
			);
        }
    }
	
	public function authorSave(){
		
		$data = Input::json();
		
		$credentials = array(
				'username' => $data->get('username'),
				'password' => $data->get('password'),
			);
		
		//die("Credentials: ".$data->get('username')."   ".$data->get('password'));
		
		if(Auth::attempt($credentials)){
			$authorJson = $data->get('author');
			$authorWorksJson = $data->get('authorWorks');
			
			$authorObj = json_decode($authorjson);
			$authorWorksArray = json_decode($authorWorksJson);
			
			$author = new Author();
			foreach($authorWorksArray as $authorWorkObj){
				$authorWork = new AuthorWork();
				$authorWork.setTitle($authorWorkObj["title"]);
				$authorWork.setAuthors($authorWorkObj["authors"]);
				$authorWork.setPubliser($authorWorkObj["publisher"]);
				$authorWork.setCitationsUrl($authorWorkObj["citationsUrl"]);
				$authorWork.setRankPublisher($authorWorkObj["rankPublisher"]);
				$authorWork.setCitations($authorWorkObj["citations"]);
				$authorWork.setCitationsUrl($authorWorkObj["citationsUrl"]);
				$authorWork.setYear($authorWorkObj["year"]);    
				
				$citingWorkObjsArray = $authorWorkObj["citingWorks"];
				$citingWorkArray = array();
				foreach($citingWorksArray as $citingWorkObj){
					$citingWork = new CitingWork();
					$citingWork.setName($authorWorkObj["name"]);
					$citingWork.setPublisher($authorWorkObj["publisher"]);
					$citingWork.setRankPublisher($authorWorkObj["rankPublisher"]);
					$citingWork.setAuthors($authorWorkObj["authors"]);
					$citingWork.setYear($authorWorkObj["year"]); 
					array_push($citingWorkArray, $citingWork);
				}
				$authorWork.citingWorks($citingWorkArray);    
			}
			
			// TODO
			// Convert to model (Author, AuthorWork, CitingWork)
			// Save
		
			return Response::json(
				array(
					'result' => 'ok',
					'msg' => 'Data saved successfully.'
				),
				200
				);
        }else{
        	return Response::json(array(
				'result' => 'error',
				'msg' => 'Invalid credentials.'),
				200
			);
        }
    }
	
	public function authorCache(){
		
		$data = Input::json();
		
		$credentials = array(
				'username' => $data->get('username'),
				'password' => $data->get('password'),
			);
		
		//die("Credentials: ".$data->get('username')."   ".$data->get('password'));
		
		if(Auth::attempt($credentials)){
			$author = Author::whereRaw('url ='.$data->get('authorUrl'))->orderBy('timestamp', 'ASC')->get(0)->toJson();
			$authorWorls = AuthorWorks:where('author_id', '=', $author->getId())->toJson();
			return Response::json(
				array(
					'author' => $author,
					'authorWorks' => $authorWorls
				),
				200
				);
        }else{
        	return Response::json(array(
				'error' => 'invalid credentials'),
				200
			);
        }
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
