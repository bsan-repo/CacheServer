<?php

class AuthorsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
    }

    public function basicTest() {

        $data = Input::json();

        $credentials = array(
            'username' => $data->get('username'),
            'password' => $data->get('password'),
        );

        //die("Credentials: ".$data->get('username')."   ".$data->get('password'));

        if (Auth::attempt($credentials)) {

            return Response::json(
                            array(
                        'error' => 'no errors',
                        'data' => $data->get('username')
                            ), 200
            );
        } else {
            return Response::json(array(
                        'error' => 'invalid credentials'), 200
            );
        }
    }

    public function getAuthorToProcessFromServer() {
                
        $data = Input::json();

        $credentials = array(
            'username' => $data->get('username'),
            'password' => $data->get('password'),
        );

        //die("Credentials: ".$data->get('username')."   ".$data->get('password'));

        if (Auth::attempt($credentials)) {
            $authorToProcessQueryResults = AuthorToProcess::whereRaw("date_add(processing, interval 1 day) order by processing ASC LIMIT 1")->get();
            
            if (is_object($authorToProcessQueryResults) && get_class($authorToProcessQueryResults) == "Illuminate\\Database\\Eloquent\\Collection" && !$authorToProcessQueryResults->isEmpty()) {
                $authorToProcess = $authorToProcessQueryResults[0];
                $authorToProcess->processing = date('Y-m-d h:i:s');
                $authorToProcess->save();


                return Response::json(
                                array(
                            'result' => 'ok',
                            'authorUrl' => $authorToProcess->url,
                            'delay' => $authorToProcess->delay
                                ), 200
                );
            } else {
                return Response::json(array(
                            'result' => 'no_records_found' . json_encode($authorToProcessQueryResults)), 200
                );
            }
        } else {
            return Response::json(array(
                        'error' => 'invalid credentials'), 200
            );
        }
    }

    public function authorSave() {
        /* $content = Request::json();

          return Response::json(
          array(
          'result' => '$credentials ok',
          'msg' => 'Data:'.Request::header('Content-Type')."  isJson: ".Request::isJson().  serialize($content)
          ),
          200
          ); */

        $data = Input::json();

        $credentials = array(
            'username' => $data->get('username'),
            'password' => $data->get('password'),
        );

        if (Auth::attempt($credentials)) {
            $authorJson = $data->get('author');
            $authorWorksJson = $data->get('authorWorks');

            //$authorObj = json_decode($authorJson);
            //$authorWorksArray = json_decode($authorWorksJson);

            log::info("JSON recevied data AUTHOR: " . json_encode($authorJson));
            log::info("JSON recevied data WORKS: " . json_encode($authorWorksJson));

            $authorsSaved = 0;
            $worksSaved = 0;
            $citationsSaved = 0;
            $CitingWorkText = "";

            $msgCurrentEntry = "";

            try {
                DB::beginTransaction();
                $author = new Author();
                $author->name = $authorJson["name"];
                $author->url = $authorJson["url"];
                $author->affiliation = $authorJson["affiliation"];
                $author->save();
                log::info("Saved author: " . $author->id);
                $authorsSaved++;
                foreach ($authorWorksJson as $authorWorkObj) {
                    $authorWork = new AuthorWork();
                    $authorWork->title = $authorWorkObj["title"];
                    $authorWork->authors = $authorWorkObj["authors"];
                    $authorWork->publisher = $authorWorkObj["publisher"];
                    $authorWork->publisher_in_google = $authorWorkObj["publisherInGoogle"];
                    $authorWork->citations_url = $authorWorkObj["citationsUrl"];
                    $authorWork->rank_publisher = $authorWorkObj["rankPublisher"];
                    $authorWork->citations = $authorWorkObj["citations"];
                    $authorWork->quality_citations = $authorWorkObj["qualityCitations"];
                    $authorWork->year = $authorWorkObj["year"];
                    $authorWork->author_id = $author->id;
                    $msgCurrentEntry = "Authorwork: " . $authorWorkObj["title"];
                    $authorWork->save();
                    log::info("Saved author work: " . $authorWork->id);
                    $worksSaved++;

                    $citingWorkObjsArray = $authorWorkObj["citingWorks"];
                    foreach ($citingWorkObjsArray as $citingWorkObj) {
                        $CitingWorkText = $CitingWorkText . $citingWorkObj["name"];
                        $citingWork = new CitingWork();
                        $citingWork->name = $citingWorkObj["name"];
                        $citingWork->publisher = $citingWorkObj["publisher"];
                        $citingWork->publisher_in_google = $citingWorkObj["publisherInGoogle"];
                        $citingWork->publisher_in_external_web = $citingWorkObj["publisherInExternalWeb"];
                        $citingWork->rank_publisher = $citingWorkObj["rankPublisher"];
                        $citingWork->authors = $citingWorkObj["authors"];
                        $citingWork->year = $citingWorkObj["year"];
                        if (array_key_exists("url", $citingWork)) {
                            $citingWork->url = $citingWorkObj["url"];
                        }
                        $citingWork->author_work_id = $authorWork->id;
                        $msgCurrentEntry = "Citingwork: " . $citingWorkObj["name"];
                        $citingWork->save();
                        log::info("Saved citing work: " . $citingWork->id);
                        $citationsSaved++;
                        unset($citingWork);
                    }
                    unset($authorWork);
                }
                DB::commit();
                DB:delete("delete from authors_to_process where url=".$author->url);
            } catch (Exception $ex) {
                // DB::rollBack(); Performed automatically if there is an exception
                return Response::json(
                                array(
                            'result' => 'Exception: |||||||| ' . $msgCurrentEntry . ' |||||||| ',
                            'msg' => $ex->getMessage()
                                ), 200
                );
            }


            /* DEBUG
              // TODO Add exceptions ErrorException Undefined offset / Undefined index
              return Response::json(
              array(
              'result' => '$credentials ok',
              'msg' => 'Data saved: Authors('.$authorsSaved.'), works('.$worksSaved.'), citations('.$citationsSaved.')        '.$CitingWorkText
              ),
              200
              );
             */



            // TODO
            // Convert to model (Author, AuthorWork, CitingWork)
            // Save

            return Response::json(
                            array(
                        'result' => 'ok',
                        'msg' => 'Data saved successfully.'
                            ), 200
            );
        } else {
            return Response::json(array(
                        'result' => 'error',
                        'msg' => 'Invalid credentials.' . '   DATA: ' . implode(Input::all())), 200
            );
        }
    }

    public function authorCache() {
        $data = Input::json();

        $credentials = array(
            'username' => $data->get('username'),
            'password' => $data->get('password'),
        );

        //die("Credentials: ".$data->get('username')."   ".$data->get('password'));

        if (Auth::attempt($credentials)) {
            $authorUrl = $data->get('authorUrl');
            $authorQueryResults = Author::whereRaw("url = '" . $authorUrl . "' order by updated_at DESC LIMIT 1")->get();
            if (is_object($authorQueryResults) && get_class($authorQueryResults) == "Illuminate\\Database\\Eloquent\\Collection" && !$authorQueryResults->isEmpty()) {
                $author = $authorQueryResults[0];
                $authorWorksQueryResults = AuthorWork::whereRaw("author_id = '" . $author->id . "' order by citations")->get();

                if (is_object($authorWorksQueryResults) && get_class($authorWorksQueryResults) == "Illuminate\\Database\\Eloquent\\Collection" && !$authorWorksQueryResults->isEmpty()) {
                    foreach ($authorWorksQueryResults as $authorWork) {
                        $citingWorks = $authorWork->citingWorks;
                    }

                    return Response::json(
                                    array(
                                'result' => 'ok',
                                'authorUrl ' => $authorUrl,
                                'authorSelected' => json_encode($author),
                                'authorWorks' => json_encode($authorWorksQueryResults)
                                    ), 200
                    );
                }
            } else {
                return Response::json(array(
                            'result' => 'no_records_found ' . json_encode($authorQueryResults)), 200
                );
            }
        } else {
            return Response::json(array(
                        'error' => 'invalid credentials'), 200
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
