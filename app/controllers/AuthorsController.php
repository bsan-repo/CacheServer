grim
    <?php

class AuthorsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
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
		
		if(Auth::attempt($credentials)){
			$authorJson = $data->get('author');
			$authorWorksJson = $data->get('authorWorks');
			
			//$authorObj = json_decode($authorJson);
			//$authorWorksArray = json_decode($authorWorksJson);
                        
                        log::info("JSON recevied data AUTHOR: ".  json_encode($authorJson));
                        log::info("JSON recevied data WORKS: ".  json_encode($authorWorksJson));
                        
                        $authorsSaved = 0;
                        $worksSaved = 0;
                        $citationsSaved = 0;
                        $CitingWorkText = "";
		
			
			$author = new Author();
                        $author->name = $authorJson["name"];
                        $author->url = $authorJson["url"];
                        $author->affiliation = $authorJson["affiliation"];
                        $author->save();
                        log::info("Saved author: ".$author->id);
                        $authorsSaved++;
			foreach($authorWorksJson as $authorWorkObj){
				$authorWork = new AuthorWork();
				$authorWork->title = $authorWorkObj["title"];
				$authorWork->authors = $authorWorkObj["authors"];
				$authorWork->publisher = $authorWorkObj["publisher"];
				$authorWork->citations_url = $authorWorkObj["citationsUrl"];
				$authorWork->rank_publisher = $authorWorkObj["rankPublisher"];
				$authorWork->citations = $authorWorkObj["citations"];
				$authorWork->quality_citations = $authorWorkObj["qualityCitations"];
				$authorWork->year = $authorWorkObj["year"];   
				$authorWork->author_id = $author->id;  
                                $authorWork->save();
                                log::info("Saved author work: ".$authorWork->id);
                                $worksSaved++;
				
				$citingWorkObjsArray = $authorWorkObj["citingWorks"];
				foreach($citingWorkObjsArray as $citingWorkObj){
                                    $CitingWorkText = $CitingWorkText.$citingWorkObj["name"];
					$citingWork = new CitingWork();
					$citingWork->name = $citingWorkObj["name"];
					$citingWork->publisher = $citingWorkObj["publisher"];
					$citingWork->rank_publisher = $citingWorkObj["rankPublisher"];
					$citingWork->authors = $citingWorkObj["authors"];
					$citingWork->year = $citingWorkObj["year"];
                                        $citingWork->authorwork_id = $authorWork->id;
                                        $citingWork->save();
                                        log::info("Saved citing work: ".$citingWork->id);
                                        $citationsSaved++;
				}
			}
                        
                        
                        
                        
                        // TODO Add exceptions ErrorException Undefined offset / Undefined index
			return Response::json(
				array(
					'result' => '$credentials ok',
					'msg' => 'Data saved: Authors('.$authorsSaved.'), works('.$worksSaved.'), citations('.$citationsSaved.')        '.$CitingWorkText
				),
				200
				);
                        
                        
                        
			
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
				'msg' => 'Invalid credentials.'.'   DATA: '.  json_encode($data)),
				200
			);
        }
    }
	
	public function authorCache(){
		
		
			return Response::json(
				array(
					'result' => 'ok',
					'msg' => 'Data cached successfully.'
				),
				200
				);
		/*
		$data = Input::json();
		
		$credentials = array(
				'username' => $data->get('username'),
				'password' => $data->get('password'),
			);
		
		//die("Credentials: ".$data->get('username')."   ".$data->get('password'));
		
		if(Auth::attempt($credentials)){
			$author = Author::whereRaw('url ='.$data->get('authorUrl'))->orderBy('timestamp', 'ASC')->get(0)->toJson();
			$authorWorls = AuthorWorks::where('author_id', '=', $author->getId())->toJson();
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
        }*/
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
