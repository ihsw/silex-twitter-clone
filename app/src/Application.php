<?php namespace Ihsw\TwitterClone;

use Silex\Application as SilexApplication;

class Application extends SilexApplication
{
    public function getPosts()
    {
        return $this["pdo"]->query("SELECT id, occurredAt, content FROM Post")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createPost($content)
    {
        $statement = $this["pdo"]->prepare("INSERT INTO Post (occurredAt, content) VALUES (:occurredAt, :content)");
        $statement->execute(["content" => $content, "occurredAt" => date("Y-m-d H:i:s")]);
    }
}