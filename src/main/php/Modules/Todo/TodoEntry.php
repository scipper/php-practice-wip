<?php declare(strict_types = 1);

namespace Mys\Modules\Todo;

use JsonSerializable;
use stdClass;

class TodoEntry implements JsonSerializable {

    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $title;

    /**
     * @param int $id
     * @param string $title
     */
    public function __construct(int $id, string $title) {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass {
        $json = new stdClass();
        $json->id = $this->getId();
        $json->title = $this->getTitle();

        return $json;
    }

}