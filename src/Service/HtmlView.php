<?php
namespace App;

#**************************************************************************************************************************************#/

class HtmlView {

#**************************************************************************************************************************************#/

public $header;
public $body;
public $HTMLheader;
public $HTMLbody;
public $view, $edit, $delete;



    

#**************************************************************************************************************************************#/

    public function __construct($headers, $bodies, $view, $edit, $delete)
    {
        $this->header = $headers;
        $this->body = $bodies;
        $this->HTMLheader = null;
        $this->HTMLbody = null;
        $this->view = $view;
        $this->edit = $edit;
        $this->delete = $delete;

    }

#**************************************************************************************************************************************#/

public function TableHeader ()

 {
     $temp=null;

     foreach ($this->header as $element)  {

        $temp .= <<<HTML
        <th>$element</th>
HTML;

     }
    
    $this->HTMLheader = $temp;



 }
    
 

  #**************************************************************************************************************************************#/

 public function TableBody ()

 {
     $temp=null;
     $nom = null;
     
foreach ( $this->body as $ligne) {
    $nom = $ligne->getNom();
    $description = $ligne->getDescription();
    $id = $ligne->getId();
    $view = $this->view;
    
        
$temp .= <<<HTML
        <tr>
            <td> $nom </td>
            <td>$description</td>
            <td>
                <a href={{path( $this->view, {'id':"$id" } )}} title="Détails">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
            <td>
                <a href={{path( "$this->edit", {'id':"$id" } )}} title="Editer">
                    <i class="fas fa-marker"></i>
                </a>
            </td>
            <td>
                <a href={{path( "$this->delete"  {'id':"$id" } )}} onclick="return confirm('Voulez vous supprimer ce corpus?')" title="Supprimer?">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
HTML;

     
 }    

    $this->HTMLbody = $temp;


 }


 #**************************************************************************************************************************************#/

 public function createTable() {

    $this->TableHeader();
    $this->TableBody();

    return <<<HTML
    <div class="card card-cascade narrower">

        <div class="px-4">
            <div class="table-wrapper">

                <table class="table table-striped">
                    
                    <thead>
                        <tr>
                            $this->HTMLheader
                        </tr>    
                    </thead>

                    <tbody>
                         <tr>
                             $this->HTMLbody
                        </tr>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
				
HTML;

 }



#**************************************************************************************************************************************#/
public function search($add, $delete) {


    return <<<HTML
    
		<div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3
																																																																																																																																																																																				                d-flex justify-content-between align-items-center">

			<h6>Liste des corpus</h6>

			<div>

				<button type="button" class="btn btn-info">
					<a href="{{path( '$add' )}}">
						<i class='fas fa-plus' title='Ajouter un nouveau corpus'></i>
					</a>
				</button>


				<button type="button" class="btn btn-info">
					<a href="{{path('$delete')}}">
						<i class='fas fa-trash' title="Supprimer tous les corpus" onclick="return confirm('Voulez vous supprimer tous les corpus? ')"></i>
					</a>
				</button>

				<button type="button" class="btn btn-info">
					<i class="fas fa-info-circle mt-0"></i>
				</button>
			</div>

		</div>
HTML;


}



}

?>