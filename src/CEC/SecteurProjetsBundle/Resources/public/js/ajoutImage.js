$(document).ready(function() {

    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.

    var $container = $('#AlbumType_images_control_group');

    var $addLink = $('<a href="#" id="add_image" class="btn btn-default" style="margin-left:20px;">Ajouter une image</a>');
	
	// On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement

    var index = $container.find(':input').length;
    
	if(index < 15)
	{
		$container.append($addLink);
	}
	
    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.

    $addLink.click(function(e) {

      addImage($container);

      e.preventDefault(); // évite qu'un # apparaisse dans l'URL

      return false;

    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un.

    if (index == 0) {

      addImage($container);

    } else {

      // Pour chaque image déjà existante, on ajoute un lien de suppression

      $container.children('div').each(function() {

        addDeleteLink($(this));

      });

    }
	
	

    // La fonction qui ajoute un formulaire Image

    function addImage($container) {

      // Dans le contenu de l'attribut « data-prototype », on remplace :

      // - le texte "__name__label__" qu'il contient par le label du champ

      // - le texte "__name__" qu'il contient par le numéro du champ

      var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Image n°' + (index+1) )

          .replace(/__name__/g, index));

      // On ajoute au prototype un lien pour pouvoir supprimer la catégorie

      addDeleteLink($prototype);

      // On ajoute le prototype modifié à la fin de la balise <div>

      $container.append($prototype);

      // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro

      index++;

    }

    // La fonction qui ajoute un lien de suppression d'une catégorie

    function addDeleteLink($prototype) {

      // Création du lien

      $deleteLink = $('<a href="#" class="btn btn-danger pull-right">Supprimer</a>');

      // Ajout du lien

      $prototype.append($deleteLink);

      // Ajout du listener sur le clic du lien

      $deleteLink.click(function(e) {

        $prototype.remove();

        e.preventDefault(); // évite qu'un # apparaisse dans l'URL

        return false;

      });
    }
});