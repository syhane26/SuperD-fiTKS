<?php
$dataFile = 'photos_data.json';

if (!file_exists($dataFile)) {
    $initialData = [
        'photos' => [
            [
                'id' => 1,
                'title' => 'Coucher de soleil sur la Motte',
                'description' => 'Un magnifique coucher de soleil capturé au-dessus des forêts de la Motte',
                'author' => 'Jean Martin',
                'votes' => 0
            ],
            [
                'id' => 2,
                'title' => 'Faune sauvage',
                'description' => 'Un cerf en plein cœur de son habitat naturel',
                'author' => 'Marie Dupont',
                'votes' => 0
            ],
            [
                'id' => 3,
                'title' => 'Flore printanière',
                'description' => 'Les fleurs sauvages de la Motte au printemps',
                'author' => 'Pierre Leclerc',
                'votes' => 0
            ]
        ]
    ];
    file_put_contents($dataFile, json_encode($initialData, JSON_PRETTY_PRINT));
}


$data = json_decode(file_get_contents($dataFile), true);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photo_id'])) {
    $photoId = (int)$_POST['photo_id'];
    foreach ($data['photos'] as &$photo) {
        if ($photo['id'] === $photoId) {
            $photo['votes']++;
            break;
        }
    }
    

    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
    

    header('Location: concours.php');
    exit;
}


usort($data['photos'], function($a, $b) {
    return $b['votes'] - $a['votes'];
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Concours Photo - La Motte</title>
  <link rel="stylesheet" href="Style.css">
</head>
<body>
  <header>
    <h1>La Motte</h1>
    <p>Espace naturel préservé</p>
    <nav>
      <a href="../SuperD-fiTKS/index.html">Accueil</a> | 
      <a href="../SuperD-fiTKS/details.html">Détails de l'espace</a> | 
      <a href="../SuperD-fiTKS/index.php">Concours photo</a>
    </nav>
  </header>

  <main>
    <h2>Concours Photo</h2>
    <p>Votez pour votre photo préférée ! Chaque vote compte pour déterminer le gagnant du concours photo de la Motte.</p>
    
    <div class="photos-list">
      <?php foreach ($data['photos'] as $photo): ?>
        <div class="photo-item">
          <h3><?php echo htmlspecialchars($photo['title']); ?></h3>
          <p><strong>Auteur :</strong> <?php echo htmlspecialchars($photo['author']); ?></p>
          <p><?php echo htmlspecialchars($photo['description']); ?></p>
          <p><strong>Votes : <?php echo $photo['votes']; ?></strong></p>
          
          <form method="POST" action="concours.php" style="display:inline;">
            <input type="hidden" name="photo_id" value="<?php echo $photo['id']; ?>">
            <button type="submit">Voter pour cette photo</button>
          </form>
        </div>
        <hr>
      <?php endforeach; ?>
    </div>
    
    <h3>Classement actuel</h3>
    <ol>
      <?php foreach ($data['photos'] as $photo): ?>
        <li><?php echo htmlspecialchars($photo['title']); ?> - <?php echo $photo['votes']; ?> vote(s)</li>
      <?php endforeach; ?>
    </ol>
  </main>

  <footer>
    <hr>
    <p>&copy; 2025 La Motte - Espace Naturel</p>
    <p>Email : info@lamotte.fr</p>
  </footer>
</body>
</html>