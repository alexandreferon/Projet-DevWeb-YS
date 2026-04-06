<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>formulaire</title>
    
  </head>
</head>


<form>
    <label for="">Titre</label>
    <input required type="text" name = "" placeholder="Nom.." id = "">
    <br><br>
    <label for="">Prix</label>
    <input required type="in" name = "" placeholder="Prix">
    <br><br>
    <label for="">état</label>
    <select name="Type" id = "">
            <option value="Neuf">Neuf</option>
            <option value="TB_etat">Trés bon état</option>
            <option value="B_etat">Bon état</option>
            <option value="Satisfaisant">Satisfaisant</option>
    </select>
    <br><br>
    <label for="" >description</label>
    <br><br>    
    <textarea required name="" placeholder="" id=""></textarea>S
    <br><br>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*">
    <button type="submit">Envoyer</button>
    </form>
</form>
