<?php

$dsn = "mysql:host=localhost;dbname=leaves";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
function createDocument($title, $content, $preparedBy) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO Documents (title, content, prepared_by) VALUES (:title, :content, :preparedBy)");
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':preparedBy' => $preparedBy
    ]);
    return $pdo->lastInsertId();
}

function isOnLeave($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT is_on_leave FROM Users WHERE user_id = :userId");
    $stmt->execute([':userId' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($result['is_on_leave']) ? $result['is_on_leave'] : false;
}

function getSubstitute($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT substitute_id FROM Substitutes WHERE user_id = :userId");
    $stmt->execute([':userId' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($result['substitute_id']) ? $result['substitute_id'] : null;
}

function approveDocument($documentId, $managerId) {
    global $pdo;

    if (isOnLeave($managerId)) {
        $substituteId = getSubstitute($managerId);
        if ($substituteId) {
            $stmt = $pdo->prepare("UPDATE Documents SET approved_by = :substituteId, approved_substitute = :substituteId, approved_date = NOW() WHERE document_id = :documentId");
            $stmt->execute([':substituteId' => $substituteId, ':documentId' => $documentId]);
            return "Document approved by substitute (Manager is on leave)";
        } else {
            return "Manager and substitute unavailable.";
        }
    } else {
        $stmt = $pdo->prepare("UPDATE Documents SET approved_by = :managerId, approved_date = NOW() WHERE document_id = :documentId");
        $stmt->execute([':managerId' => $managerId, ':documentId' => $documentId]);
        return "Document approved by Manager.";
    }
}

function confirmDocument($documentId, $directorId) {
    global $pdo;

    if (isOnLeave($directorId)) {
        $substituteId = getSubstitute($directorId);
        if ($substituteId) {
            $stmt = $pdo->prepare("UPDATE Documents SET confirmed_by = :substituteId, confirmed_substitute = :substituteId, confirmed_date = NOW() WHERE document_id = :documentId");
            $stmt->execute([':substituteId' => $substituteId, ':documentId' => $documentId]);
            return "Document confirmed by substitute (Director is on leave)";
        } else {
            return "Director and substitute unavailable.";
        }
    } else {
        $stmt = $pdo->prepare("UPDATE Documents SET confirmed_by = :directorId, confirmed_date = NOW() WHERE document_id = :documentId");
        $stmt->execute([':directorId' => $directorId, ':documentId' => $documentId]);
        return "Document confirmed by Director.";
    }
}

$documentId = createDocument("Example Title", "Example content", 1);
echo approveDocument($documentId, 2); // 2 to ID Kierownika
echo confirmDocument($documentId, 3); // 3 to ID Dyrektora


