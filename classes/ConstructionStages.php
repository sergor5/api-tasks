<?php

require __DIR__ . '/../helpers/calc_duration.php';

/** 
 * Name: Construction Stages Class
 * Description: This class is used to handle all construction stages related requests.
 * @property PDO $db The database connection
 * 
 * @method array getAll() Get all construction stages
 * @method object getSingle($id) Get a single construction stage
 * @method object create($data) Create a new construction stage
 * @method object update($id, $data) Update a construction stage
 * @method object delete($id) Delete a construction stage
 * 
 */
class ConstructionStages
{
	private $db;

	public function __construct()
	{
		$this->db = Api::getDb();
	}

	public function getAll()
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
		");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSingle($id)
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE ID = :id
		");
		$stmt->execute(['id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	public function post(ConstructionStagesCreate $data)
	{
		$rules = [
			'name' => array('required', 'maxlen:255'),
			'startDate' => array('required', 'date'),
			'endDate' => array('date_after:$startDate'),
			'durationUnit' => array('enum:HOURS,DAYS,WEEKS'),
			'color' => array('hex'),
			'externalId' => array('maxlen:255'),
			'status' => array('enum:NEW,PLANNED,DELETED'),
		];

		$validator = new Validator(get_object_vars($data), $rules);

		$defaults = [
			'duration' => NULL,
			'durationUnit' => 'DAYS',
			'status' => 'NEW',
		];

		$data = array_merge($defaults, get_object_vars($data));
		$data = (object) $data;

		$data->duration = calc_duration($data->startDate, $data->endDate, $data->durationUnit);

		if ($validator->validate() === false) {
			return ["code" => 400, "message" => "Bad Request", "errors" => $validator->getErrors()];
		} else {
			$stmt = $this->db->prepare("
			INSERT INTO construction_stages
			    (name, start_date, end_date, duration, durationUnit, color, externalId, status)
			    VALUES (:name, :start_date, :end_date, :duration, :durationUnit, :color, :externalId, :status)
			");
			$stmt->execute([
				'name' => $data->name,
				'start_date' => $data->startDate,
				'end_date' => $data->endDate,
				'duration' => $data->duration,
				'durationUnit' => $data->durationUnit,
				'color' => $data->color,
				'externalId' => $data->externalId,
				'status' => $data->status,
			]);
			return $this->getSingle($this->db->lastInsertId());
		}
	}

	public function patch(ConstructionStagesPatch $data, $id)
	{

		$rules = [
			'name' => array('maxlen:255'),
			'startDate' => array('date'),
			'endDate' => array('date_after:$startDate'),
			'durationUnit' => array('enum:HOURS,DAYS,WEEKS'),
			'color' => array('hex'),
			'externalId' => array('maxlen:255'),
			'status' => array('enum:NEW,PLANNED,DELETED'),
		];

		$validator = new Validator(get_object_vars($data), $rules);

		$defaults = [
			'duration' => NULL,
			'durationUnit' => 'DAYS',
			'status' => 'NEW',
		];

		$data = array_merge($defaults, get_object_vars($data));
		$data = (object) $data;

		$data->duration = calc_duration($data->startDate, $data->endDate, $data->durationUnit);

		if ($validator->validate() === false) {
			return ["code" => 400, "message" => "Bad Request", "errors" => $validator->getErrors()];
		} else {
			if (empty($data))
				throw new Exception('No data to update');

			$allowedFields = [
				'name',
				'start_date',
				'end_date',
				'duration',
				'durationUnit',
				'color',
				'externalId',
				'status'
			];

			$obj_vars = get_object_vars($data);

			// remove empty fields
			foreach ($obj_vars as $key => $value) {
				if ($value === '' || $value === null) {
					unset($obj_vars[$key]);
				}
			}

			$data = array_intersect_key($obj_vars, array_flip($allowedFields));


			if (isset($data['status']) && !in_array($data['status'], ['NEW', 'PLANNED', 'DELETED']))
				throw new Exception('Invalid status');

			// build query
			$query = "UPDATE construction_stages SET ";

			// add fields to query for the statement
			foreach ($data as $key => $value) {
				$query .= $key . ' = :' . $key . ', ';
			}

			// remove last comma
			$query = rtrim($query, ', ');

			$query .= " WHERE ID = :id";

			$stmt = $this->db->prepare($query);
			$data["id"] = $id;

			$stmt->execute($data);

			$result = $this->getSingle($id);
			if (!empty($result))
				return $result;
			else
				return ["code" => 404, "status" => "error", "message" => "Row not found"];
		}

	}

	public function delete($id)
	{
		$stmt = $this->db->prepare("
			UPDATE construction_stages SET status = 'DELETED' WHERE ID = :id AND status != 'DELETED'
		");
		$stmt->execute(['id' => $id]);
		if ($stmt->rowCount() > 0)
			return ["code" => 200, "status" => "success", "message" => "Row deleted"];
		else
			return ["code" => 404, "status" => "error", "message" => "Row not found"];
	}
}