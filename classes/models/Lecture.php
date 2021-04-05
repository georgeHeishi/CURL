<?php


class Lecture
{
    private int $id;
    private string $timestamp;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }



    public function getRowHead()
    {
        return '<th scope="col" id=lecture' . $this->id . '">
                Prednáška č.' . $this->id . ' <br>
                ' . date("d-m-Y", strtotime($this->timestamp)) . '
            </th>';
    }
}