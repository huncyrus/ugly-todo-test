<?php
require_once 'config.php';
require 'vendor/autoload.php';
require_once 'src/dbHelper.php';
require_once 'src/templateHelper.php';

$app = new \Slim\Slim();
$dbHelper = new \todo\databaseHelper(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$db = $dbHelper->getDB();
$template = new \todo\templateHelper(TEMPLATE_DIR);


/**
 * Index
 */
$app->get('/', function() use($app, $template) {
	$app->response->setStatus(200);
	$template->render('index.html');
});


/**
 * Get all list element, ordered by position field
 */
$app->get('/getlist/', function () use ($app, $db) {
	$app->response->setStatus(200);
	$app->response()->headers->set('Content-Type', 'application/json');
	$out = '';

	try {
		$sql = '
			SELECT
				edukey_todo.*
			FROM
				edukey_todo
			WHERE
				edukey_todo.title <> ""
			ORDER BY
				edukey_todo.position
			ASC;
		';

		$query = $db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_OBJ);
		if ($result) {
			$out = $result;
		//} else {
		//	throw new PDOException('No records found.');
		}
	} catch(PDOException $e) {
		$app->response()->setStatus(404);
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}

	echo json_encode($out);
});


/**
 * Add list element, ordered by position field
 */
$app->post('/additem/', function () use ($app, $db) {
	$app->response->setStatus(200);
	$app->response()->headers->set('Content-Type', 'application/json');
	$out = '';

	try {
		$post = $app->request->post();

		if (isset($post['title'])) {
			$itemTitle = strip_tags(htmlspecialchars($post['title']));
		}

		if (!empty($itemTitle) && is_string($itemTitle)) {
			$sql = '
				INSERT INTO
					edukey_todo
				(
				    edukey_todo.title,
				    edukey_todo.position
				)
				VALUES (
					"' . $itemTitle . '",
					"0"
				);
			';

			$query = $db->prepare($sql);
			$query->execute();

			$out = 'OK';
		} else {
			$app->response()->setStatus(400);
			$out = '{"error":{"text":"Bad request. No param..."}}';
		}
	} catch(PDOException $e) {
		$app->response()->setStatus(404);
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}

	echo json_encode($out);
});


/**
 * Get all list element, ordered by position field
 */
$app->get('/removeitem/:id/', function ($id) use ($db, $app) {
	$app->response->setStatus(200);
	$app->response()->headers->set('Content-Type', 'application/json');
	$out = '';

	try {
		$sql = '
			SELECT
				edukey_todo.*
			FROM
				edukey_todo
			WHERE
				edukey_todo.id = :id
		';

		$query = $db->prepare($sql);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);

		// check the item exists or not
		if ($result) {
			$sql = '
				DELETE FROM
					edukey_todo
				WHERE
					edukey_todo.id = :id
			';

			$query = $db->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			$out = 'OK';
		} else {
			throw new PDOException('No records found.');
		}
	} catch(PDOException $e) {
		$app->response()->setStatus(404);
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}

	echo json_encode($out);
});


/**
 * Set item position element, ordered by position field
 */
$app->post('/setposition/', function () use ($app, $db) {
	$app->response->setStatus(200);
	$app->response()->headers->set('Content-Type', 'application/json');
	$out = '';

	try {
		$post = $app->request->post();
		if (isset($post['item'])) {
			$itemList = $post['item'];
		}

		if (!empty($itemList) && is_array($itemList)) {
			$i = 0;

			foreach ($itemList as $item) {
				if (!empty($item)) {
					$id = (int) strip_tags(htmlspecialchars($item));

					$sql = '
						SELECT
							edukey_todo.*
						FROM
							edukey_todo
						WHERE
							edukey_todo.title <> ""
						AND
							edukey_todo.id = "' . $id . '"
					';

					$query = $db->prepare($sql);
					$query->execute();
					$result = $query->fetch(PDO::FETCH_OBJ);

					// check the item exists or not
					if ($result) {
						$sql = '
							UPDATE
								edukey_todo
							SET
								edukey_todo.position = "' . $i . '"
							WHERE
								edukey_todo.id = "' . $id . '"
						';
						$query = $db->prepare($sql);
						$query->execute();
						$i++;
					}
				}
			}

			$out = 'OK';
		} else {
			$app->response()->setStatus(400);
			$out = '{"error":{"text":"Bad request. No param..."}}';
		}
	} catch(PDOException $e) {
		$app->response()->setStatus(404);
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}

	echo json_encode($out);
});


$app->run();
