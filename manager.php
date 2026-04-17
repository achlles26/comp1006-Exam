<?php

    Session_start();

    $sql = "SELECT * from images1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $images = $stmt->fetch();
?>

<main>
    <h2>Images</h2>
    <?php if (empty($images)): ?>
        <p>No images available yet.</p>
    <?php else: ?>
        <div>
            <?php foreach ($images as $image): ?>
                <div>
                    <div>
                        <?php if (!empty($image['image_path'])): ?>
                            <img
                                src="<?= htmlspecialchars($image['image_path']); ?>"
                                alt="<?= htmlspecialchars($image['image_title']); ?>">
                        <?php endif; ?>

                        <div>
                            <h3>
                                <?= htmlspecialchars($image['image_title']); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>