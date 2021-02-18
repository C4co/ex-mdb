<?php namespace App\Database;

class Database
{
    public static string $path = ".database-temp";
    private string $databasePath = ".database-temp";
    private string $databaseFormat = ".json";
    private string $databaseName;
    private string $databaseLocal;

    public function __construct(string $name)
    {
        $this->databaseName = $name;
        $this->databaseLocal = $this->databasePath . "/" . $this->databaseName . $this->databaseFormat;
        $this->checkDatabase();
    }

    public function getInfo()
    {
        return (object)[
            "name" => $this->databaseName,
            "path" => $this->databasePath,
            "local" => $this->databaseLocal,
            "format" => $this->databaseFormat
        ];
    }

    public function checkDatabase()
    {
        try {
            $db_model = array("data" => []);

            if (!is_dir($this->databasePath)) {
                mkdir($this->databasePath);
            }

            if (!file_exists($this->databaseLocal)) {
                file_put_contents($this->databaseLocal, json_encode($db_model));
            }
        } catch (Exception $error) {
            echo "Database check -> " . $error->getMessage();
        }
    }

    public function add(array $element)
    {
        try {
            $content = json_decode(file_get_contents($this->databaseLocal));
            $element["id"] = uniqid();

            array_push($content->data, $element);

            file_put_contents($this->databaseLocal, json_encode($content));

            return $this;
        } catch (Exception $error) {
            echo "Database check -> " . $error->getMessage();
        }
    }

    public function getAll()
    {
        try {
            $content = json_decode(file_get_contents($this->databaseLocal));

            return $content;
        } catch (Exception $error) {
            echo "Database get all -> " . $error->getMessage();
        }
    }

    public function query(callable $condition)
    {
        try {
            $content = $this->getAll();
            $result = array_filter($content->data, $condition);

            return array_values($result);
        } catch (Exception $error) {
            echo "Database get all -> " . $error->getMessage();
        }
    }

    public function update(string $id, array $newValue)
    {
        try {
            $content = $this->getAll();
            $element = array_filter($content->data, fn ($item) => $item->id === $id);

            if (count($element) === 0) {
                return false;
            }

            $index = array_keys($element)[0];
            $content->data[$index] = array_merge((array)$content->data[$index], $newValue);
            file_put_contents($this->databaseLocal, json_encode($content));

            return true;
        } catch (Exception $error) {
            echo "Database update -> " . $error->getMessage();
        }
    }

    public function delete($selected)
    {
        try {
            $content = $this->getAll();
            $element = array_filter($content->data, fn ($item) => $item->id === $selected->id);

            if (count($element) === 0) {
                return false;
            }

            $index = array_keys($element)[0];
            unset($content->data[$index]);

            $updatedContent = ["data" => array_values($content->data)];
            file_put_contents($this->databaseLocal, json_encode($updatedContent));

            return true;
        } catch (Exception $error) {
            echo "Database delete -> " . $error->getMessage();
        }
    }
}
