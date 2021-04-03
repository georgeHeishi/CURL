<?php
require_once(__DIR__ . "/../models/AttendanceDetail.php");

class StudentDetail
{
    private string $name;

//  array stucture:
//  [lecture_id] => {
//                  [joined] => left (may be multiple records)
//                  [disconnected] => true/false
//                  }
    private array $attendance;

    private $totalAttendance;

    private $totalTimeAttendance;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getAttendance(): array
    {
        return $this->attendance;
    }

    /**
     * @param array $attendance
     */
    public function setAttendance(array $attendance): void
    {
        $this->attendance = $attendance;
    }

    /**
     * @return mixed
     */
    public function getTotalAttendance()
    {
        return $this->totalAttendance;
    }

    /**
     * @param mixed $totalAttendance
     */
    public function setTotalAttendance($totalAttendance): void
    {
        $this->totalAttendance = $totalAttendance;
    }

    /**
     * @return mixed
     */
    public function getTotalTimeAttendance()
    {
        return $this->totalTimeAttendance;
    }

    /**
     * @param mixed $totalTimeAttendance
     */
    public function setTotalTimeAttendance($totalTimeAttendance): void
    {
        $this->totalTimeAttendance = $totalTimeAttendance;
    }


    public function attendanceToColumns($columnCount): string
    {
        $this->totalAttendance = 0;
        $this->totalTimeAttendance = 0;
        $result = "";

        for ($i = 1; $i <= $columnCount; $i++) {
            $minutes = 0;
            if (isset($this->attendance[$i])) {
                foreach ($this->attendance[$i] as $key => $value) {
                    if (strcmp($key, "disconnected")) {
                        $minutes += (strtotime($value) - strtotime($key)) / 60;
                    }
                }
            }
            $result = $result . "<td>" . $minutes . "</td>";
            if ($minutes > 0) {
                $this->totalAttendance++;
            }
            $this->totalTimeAttendance += $minutes;
        }
        return $result;
    }

    public function getRow($columnCount): string
    {
        return "<tr>
                    <td>" . $this->name . "</td>"

            . $this->attendanceToColumns($columnCount) .

            "<td>" . $this->totalAttendance . "</td>
                    <td>" . $this->totalTimeAttendance . "</td>
                </tr>";
    }

}