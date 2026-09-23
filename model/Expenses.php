<?php

namespace Model;

use PDO;
use Override;

class Expenses extends BaseRepository
{
    #[Override]
    public function __construct(PDO $conn)
    {
        parent::__construct($conn);
        $this->tableName = 'expenses';
    }
}