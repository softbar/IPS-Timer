<?php
require_once __DIR__ . '/../libs/loader.inc';
/** Timer Modul
 * @author Xavier
 * @version 1.0
 * 
 */
class Timer extends IPSModule 
{
	use TimerHelper,
		KernelHelper,
		FormHelper;
	/**
	 * {@inheritDoc}
	 * @see IPSModule::Create()
	 */
	function Create() {
		parent::Create ();
		$this->TimerHelperCreate ();
		$this->RegisterAttributeString ( 'TimerEvents', '[]' );
	}
	/**
	 * {@inheritDoc}
	 * @see IPSModule::ApplyChanges()
	 */
	function ApplyChanges() {
		parent::ApplyChanges ();
		$this->KernelHelperApplyChanges ();
		if (IPS_GetKernelRunlevel () != KR_READY) {return;}
		$this->StartTimerByEvents ( $this->LoadEvents () );
	}
	/**
	 * {@inheritDoc}
	 * @see IPSModule::GetConfigurationForm()
	 */
	function GetConfigurationForm() {
		$form = $this->FormHelperLoadForm ();
		$form->actions [1]->values = $this->GetFormEventList ();
		$form->actions [0]->visible = ! empty ( $form->actions [1]->values );
		$this->FormHelperAddPopupAlert ( $form, 'TimerAlert' );
		return json_encode ( $form );
	}
	/**
	 * {@inheritDoc}
	 * @see IPSModule::RequestAction()
	 */
	function RequestAction($Ident, $Value) {
		$ok = null;
		switch ($Ident) {
			case "CLEAR_ALL" :
				$this->SaveEvents ( [ ] );
				break;
			case "TEST" :
				$ok = $this->TestEvent ( $Value );
				break;
			case "SET" :
				$ok = $this->SetPermanent ( $Value, true );
				break;
			case "UNSET" :
				$ok = $this->SetPermanent ( $Value, false );
				break;
			case "STOP" :
				$ok = $this->Stop ( $Value, false );
				break;
			case "START" :
				$ok = $this->Start ( $Value, 0 );
				break;
			case "DELETE" :
				$ok = $this->Remove ( $Value );
				break;
			default :
				if (! $this->TimerHelperRequestAction ( $Ident, $Value )) $this->SendDebug ( __FUNCTION__, "Unknown Ident '$Ident' Value => $Value", 0 );
				;
		}
		if (! is_null ( $ok )) {
			$info = [ 
				"TEST" => "Test Timer '%s'",
				"SET" => "Set '%s' permanent",
				"UNSET" => "Unset '%s' permanent",
				"STOP" => "Stop Timer '%s'",
				"START" => "Start Timer '%s'",
				"DELETE" => "Delete Timer '%s'"
			] [$Ident] ?? "";
			$info = sprintf ( $this->Translate ( $info ), $Value ) . ' ' . (is_string ( $ok ) ? $ok : ($ok ? 'OK' : 'Error'));
			$this->FormHelperShowAlert ( 'TimerAlert', $info );
		}
	}
	/** Fügt der Liste einen neuen, deaktivierten, Timer hinzu
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param int $Seconds Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
	 * @param int $Repeats Anzahl der Wiederholungen des Timers, Standard ist 1 mal
	 * @param bool $Permanent Legt fest, ob der Timer nach Ablauf beibehalten und deaktiert, oder gelöscht wird. Standard ist das löschen nach der letzten Ausführung.
	 * @param int $VariableID ObjectID der zu schaltenden Variable, erlaubt sind Boolean, integer und float
	 * @param string $ScriptContent Entweder reiner Quellkode oder die ObjectID eine vorhandenen IPS Skript
	 * @param mixed $VariableEndValue Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
	 * @param mixed $VariableStartValue Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , keine Wert
	 * @return boolean|string Bei Erfolg liefert die Funktion true, andernfalls einen String mit dem Fehler.
	 * @note ScriptContent kann den Quellcode ohne < ?php ? > und mit abschließendem ; enthalten oder auch die ID von eines IPS Skript
	 * @note Mit Add darf der Timer nicht existieren andernfalls liefert die Funktion eine Fehlermeldung
	 */
	public function Add(string $Ident, int $Seconds, int $Repeats, bool $Permanent, int $VariableID, string $ScriptContent, $VariableEndValue, $VariableStartValue) {
		if ($this->FindEvent ( $Ident, $events = $this->LoadEvents (), true )) {return sprintf ( $this->Translate ( "Timer '%s' already exists!" ), $Ident );}
		$event = $this->CreateEvent ( $Ident, $Seconds, $Repeats, $Permanent, $ScriptContent, $VariableID, $VariableEndValue, $VariableStartValue );
		$return = $this->CheckEvent ( $event );
		if ($return === true) {
			$events [] = $event;
			$this->SaveEvents ( $events );
		}
		return $return;
	}
	/** Ändert und startet einen permanenten Skript Timer oder erstellt einen neuen normalen Skript Timer und startet diesen einmalig
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param int $Seconds Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
	 * @param string $ScriptContent Entweder reiner Quellkode oder die ObjectID eine vorhandenen IPS Skript
	 * @param mixed $VariableEndValue Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
	 * @param mixed $VariableStartValue Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , keine Wert
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.
	 * @note ScriptContent kann den Quellcode ohne < ?php ? > und mit abschließendem ; enthalten oder auch die ID von eines IPS Skript
	 * @note Ist der Timer vorhanden wird er aktualisiert wenn es ein Permanenter Timer ist und der jeweilig angegebene Parameter NICHT leer ( **0**, **''** oder **null** ) ist
	 */
	public function StartScript(string $Ident, int $Seconds, string $ScriptContent, $VariableEndValue, $VariableStartValue) {
		return $this->UpdateEvent ( $Ident, $Seconds, 1, $ScriptContent, null, $VariableEndValue, $VariableStartValue );
	}
	/** Ändert und startet einen permanenten Variable Timer oder erstellt einen neuen normalen Variable Timer und startet diesen einmalig
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param int $Seconds Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
	 * @param int $VariableID ObjectID der zu schaltenden Variable, erlaubt sind Boolean, integer und float
	 * @param mixed $VariableEndValue Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
	 * @param mixed $VariableStartValue Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , keine Wert
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.
	 * @note Ist der Timer vorhanden wird er aktualisiert wenn es ein Permanenter Timer ist und der jeweilig angegebene Parameter NICHT leer ( **0**, **''** oder **null** ) ist
	 */
	public function StartVariable(string $Ident, int $Seconds, int $VariableID, $VariableEndValue, $VariableStartValue) {
		return $this->UpdateEvent ( $Ident, $Seconds, 1, null, $VariableID, $VariableEndValue, $VariableStartValue );
	}
	/** Überprüft ob ein Timer bereits existiert
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @return boolean Wird $Ident gefunden liefert die Funktion true, sonst false.
	 */
	public function Exists(string $Ident) {
		return ! empty ( $this->FindEvent ( $Ident, $this->LoadEvents (), false ) );
	}
	/** Startet einen permanenten oder gestoppten Timer neu
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param int $Seconds Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.
	 */
	public function Start(string $Ident, int $Seconds) {
		if ($event = $this->FindEvent ( $Ident, $events = $this->LoadEvents (), true )) {
			$this->StopTimer ();
			if ($Seconds > 5) $event->interval = $Seconds;
			$event->nextRun = time () + $event->interval;
			if (! is_null ( $event->startValue )) {
				$this->ExecuteEvent ( $event, true );
			}
			$this->SaveEvents ( $events );
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	/** Stoppt den mit $Ident angegeben Timer
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param bool $SendEndEvent Wenn true wird vor den Stoppen, unabhängig von der Restlaufzeit, der Timer noch einmal ausgeführt  
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler
	 */
	public function Stop(string $Ident, bool $SendEndEvent) {
		$events = $this->LoadEvents ();
		if ($found = $this->FindEvent ( $Ident, $events, false )) {
			$this->StopTimer ();
			foreach ( $found as $eventIndex => $event ) {
				if ($SendEndEvent) $this->ExecuteEvent ( $event, false );
				if (! $event->permanent) {
					unset ( $events [$eventIndex] );
				} else
					$event->nextRun = 0;
			}
			$this->SaveEvents ( array_values ( $events ) );
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	/** Stoppt und löscht den mit $Ident angegeben Timer aus der Liste
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler
	 */
	public function Remove(string $Ident) {
		if ($found = $this->FindEvent ( $Ident, $events = $this->LoadEvents (), false )) {
			$this->StopTimer ();
			foreach ( array_keys ( $found ) as $eventIndex ) {
				unset ( $events [$eventIndex] );
			}
			$this->SaveEvents ( array_values ( $events ) );
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	/** Wird ein Timer von Permanent auf nicht Permanent gestellt und die Wiederholungen bereits 0 sind wird er sofort gelöscht
	 * Ein Permanenter Timer wird nach Ablauf der Wiederholungen nicht gelöscht sondern die Wiederholungen, sowie die Zeit, starten wieder von Anfang an. Damit kann der Timer mit Start einfach wieder von vorn beginnen. 
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param bool $Permanent Der neue Status von Permanent
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler
	 * @note Wenn ein neuer Timer erstellt wird ist er, per Vorgabe, nicht Permanent und wird nach Ablauf der Wiederholungen gelöscht
	 */
	public function SetPermanent(string $Ident, bool $Permanent) {
		if ($found = $this->FindEvent ( $Ident, $events = $this->LoadEvents (), false )) {
			$this->StopTimer ();
			foreach ( $found as $eventIndex => $event ) {
				if ($event->permanent != $Permanent) {
					if (! $Permanent && $event->nextRun == 0) {
						unset ( $events [$eventIndex] );
					} else
						$event->permanent = $Permanent;
				}
			}
			$this->SaveEvents ( array_values ( $events ) );
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	/** Ändert die Anzahl der Wiederholungen für einen aktiven oder permanenten Timer
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param int $Repeats Anzahl der Wiederholungen des Timers, Standard ist 1 mal
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler
	 */
	public function SetRepeats(string $Ident, int $Repeats) {
		if ($found = $this->FindEvent ( $Ident, $events = $this->LoadEvents (), false )) {
			$this->StopTimer ();
			foreach ( $found as $eventIndex => $event ) {
				if ($Repeats < 1 && ! $event->permanent) {
					unset ( $events [$eventIndex] );
				} else
					$event->repeats = $Repeats;
			}
			$this->SaveEvents ( array_values ( $events ) );
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	/** Liefert ein Array mit Schlüsseln und Werten des Timers
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @return array Ein Array mit Schlüsseln und Werten des Timers names $Ident oder ein leeres Array wenn der Timer nicht gefunden wurde
	 */
	public function Get(string $Ident) {
		$event = $this->FindEvent ( $Ident, $this->LoadEvents (), true );
		return $event ? json_decode ( json_encode ( $event ), true ) : [ ];
	}
	/** Setzt ein Array mit Schlüsseln und Werten des Timers
	 * @param string $Ident Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
	 * @param array $Timer Ein Array mit Schlüsseln und Werten des Timers der geändert werden soll
	 * @return boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler
	 * @note Nur hiermit ist es nachträglich möglich einen Timer Ident zu ändern. 
	 * @note Schlüssel die nicht verwendet werden können vor dem setzten entfernt werden, ungültige Schlüssel werden ignoriert
	 */
	public function Set(string $Ident, array $Timer) {
		if (empty ( $Timer )) return $this->Translate ( "ERROR Empty Timer array in TIMER_Set!" );
		if ($event = $this->FindEvent ( $Ident, $events = $this->LoadEvents (), true )) {
			foreach ( $Timer as $prop => $value ) {
				if (isset ( $event->{$prop} )) $event->{$prop} = $value;
			}
			$this->SaveEvents ( $events );
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	// Events called from KernelHelper
	private function OnKernelReady() {
		$this->ApplyChanges ();
	}
	private function OnTimer($Value) {
		$this->StopTimer ();
		$errorRepeats = 2;
		$repeatTime = null;
		$now = time ();
		$events = $this->LoadEvents ();
		$run = array_filter ( $events, function ($i) use ($now) {
			return $i->nextRun > 0 && $now >= $i->nextRun;
		} );
		foreach ( $run as $eventIndex => $event ) {
			if (! $this->EcecuteEvent ( $event )) {
				if ($errorRepeats) {
					if (empty ( $event->errorStep )) $event->errorStep = 0;
					$this->SendDebug ( __FUNCTION__, "ERROR ($event->errorStep) => " . json_encode ( $event ), 0 );
					if (++ $event->errorStep <= $errorRepeats) {
						if ($event->repeats < 2) $event->repeats ++;
						$repeatTime = $event->errorStep * 5;
					} else
						unset ( $event->errorStep );
				}
			} else {
				$event->lastRun = time ();
				unset ( $event->errorStep );
			}
			$event->repeats --;
			if ($event->repeats < 1) {
				if (! $event->permanent) {
					$this->SendDebug ( __FUNCTION__, "Remove Event while finishd => " . json_encode ( $event ), 0 );
					unset ( $events [$eventIndex] );
				} else {
					$this->SendDebug ( __FUNCTION__, "Disable Event while finishd => " . json_encode ( $event ), 0 );
					$event->nextRun = 0;
					$event->repeats = $event->repeatsInit;
				}
			} else {
				$event->nextRun = time () + ($repeatTime ?? $event->interval);
				$this->SendDebug ( __FUNCTION__, "Repat Event => " . json_encode ( $event ), 0 );
			}
		}
		$this->SaveEvents ( array_values ( $events ) );
	}
	// ****************** Event List ************************
	private $EventCache = null;
	private function LoadEvents() {
		return $this->EventCache ?? $this->EventCache = json_decode ( $this->ReadAttributeString ( 'TimerEvents' ) );
	}
	private function SaveEvents(array $Events) {
		$doReload = empty ( $Events ) || empty ( $this->EventCache );
		$this->WriteAttributeString ( 'TimerEvents', json_encode ( $this->EventCache = $Events ) );
		$this->StartTimerByEvents ( $Events );
		if ($doReload) $this->ReloadForm ();
		else $this->UpdateFormField ( 'TimerEvents', 'values', json_encode ( $this->GetFormEventList () ) );
	}
	private function StartTimerByEvents(array $Events) {
		$found = array_filter ( $Events, function ($i) {
			return $i->nextRun > 0;
		} );
		$allTimes = array_map ( function ($i) {
			return $i->nextRun;
		}, $found );
		$this->StartTimerByNext ( $allTimes );
	}
	// ****************** Event ************************
	private function FindEvent(string $Ident, array $Events, bool $Once) {
		$found = array_filter ( $Events, function ($i) use ($Ident) {
			return strcasecmp ( $i->ident, $Ident ) == 0;
		} );
		if (empty ( $found )) return null;
		return $Once ? array_shift ( $found ) : $found;
	}
	private function CreateEvent(string $Ident, $Seconds, $Repeats, bool $Permanent, $Script, $VariableID, $VariableEndValue, $VariableStartValue) {
		$event = new StdClass ();
		$event->ident = $Ident;
		$event->interval = $Seconds ?? 120;
		$event->nextRun = 0;
		$event->lastRun = 0;
		$event->repeatsInit = $Repeats ?? 1;
		$event->repeats = $event->repeatsInit;
		$event->script = $Script;
		$event->variableID = $VariableID;
		$event->endValue = $VariableEndValue;
		$event->startValue = $VariableStartValue;
		$event->permanent = $Permanent;
		return $event;
	}
	private function CheckEvent(stdClass $Event) {
		if (empty ( $Event->ident )) {return $this->Translate ( "Empty Identifier!" );}
		if (empty ( $Event->script ) && empty ( $Event->variableID )) {return $this->Translate ( "Script content or VariableID missing!" );}
		if (! empty ( $Event->script )) {
			if (is_numeric ( $Event->script ) && ! IPS_ScriptExists ( $Event->script )) {return sprintf ( $this->Translate ( "Script with ID %s not exist!" ), $Event->script );}
		}
		if ($Event->variableID) {
			if (! IPS_VariableExists ( $Event->variableID )) {return sprintf ( $this->Translate ( "Variable with ID %s not exist!" ), $Event->variableID );}
			if (IPS_GetVariable ( $Event->variableID ) ['VariableType'] > 2) {return sprintf ( $this->Translate ( "String Variable ID %s not supported!" ), $Event->variableID );}
		}
		return true;
	}
	private function TestEvent($Ident) {
		$found = array_filter ( $this->LoadEvents (), function ($i) use ($Ident) {
			return strcasecmp ( $i->ident, $Ident ) == 0;
		} );
		if (count ( $found ) > 0) {
			foreach ( $found as $event ) {
				if (! $this->EcecuteEvent ( $event )) {return sprintf ( $this->Translate ( "Error in Timer Event '%s' found!" ), $Ident );}
			}
			return true;
		}
		return sprintf ( $this->Translate ( "Timer '%s' not found!" ), $Ident );
	}
	private function UpdateEvent(string $Ident, int $Seconds, int $Repeats, $Script, $VariableID, $VariableEndValue, $VariableStartValue) {
		if ($Seconds < 1) $Seconds = 0;
		elseif ($Seconds < 5) $Seconds = 5;
		if ($Repeats < 0) $Repeats = 0;
		$result = true;
		$this->StopTimer ();
		if ($event = $this->FindEvent ( $Ident, $events = $this->LoadEvents (), true )) {
			$event->nextRun = time () + $event->interval;
			$event->repeats = $Repeats ?? $event->repeatsInit;
			$event->repeatsInit = $event->repeats;
			if ($event->permanent) {
				if ($Seconds) $event->interval = $Seconds;
				if ($Script) $event->script = $Script;
				if ($VariableID) $event->variableID = $VariableID;
				if (! is_null ( $VariableEndValue )) $event->endValue = $VariableEndValue;
				if (! is_null ( $VariableStartValue )) $event->startValue = $VariableStartValue;
			}
			$result = $this->CheckEvent ( $event );
		} else {
			$event = $this->CreateEvent ( $Ident, $Seconds, $Repeats, false, $Script, $VariableID, $VariableEndValue, $VariableStartValue );
			$result = $this->CheckEvent ( $event );
			if ($result === true) {
				$event->nextRun = time () + $event->interval;
				$events [] = $event;
				if (! is_null ( $VariableStartValue )) $this->ExecuteEvent ( $event, true );
			}
		}
		$this->SaveEvents ( $events );
		return $result;
	}
	// ****************** Formular ************************
	private function GetFormEventList() {
		$events = $this->LoadEvents ();
		$values = [ ];
		$disabled = $this->Translate ( "disabled" );
		$never = $this->Translate ( "never" );
		$yes = $this->Translate ( "yes" );
		$no = $this->Translate ( "no" );
		$running = $this->Translate ( "running..." );
		$onlyOne = $this->Translate ( "unique" );
		$dateFormat = $this->Translate ( "H:i:s  d.m.Y" );
		$getName = function($id){
			$name = IPS_GetName($id);
			if($id=IPS_GetParent($id))$name.=' ('.IPS_GetName($id).')';
			return $name;
		};
	
		foreach ( $events as $event ) {
			$value = [ 
				'ident' => $event->ident,
				'interval' => $event->interval,
				'repeats' => $event->repeats > 1 ? $event->repeats : $onlyOne,
				'lastRun' => $event->lastRun > 0 ? Date ( $dateFormat, $event->lastRun ) : ($event->nextRun ? $running : $never),
				'nextRun' => $event->nextRun > 0 ? Date ( $dateFormat, $event->nextRun ) : $disabled,
				'permanent' => $event->permanent ? $yes : $no
			];
			$value ['rowColor'] = $event->nextRun > 0 ? '#C0FFC0' : '#DFDFDF';

			if ($event->script) {
				if (is_numeric ( $event->script )) {
					if (IPS_ScriptExists ( $event->script )) {
						$value ['script'] = $getName ( $event->script );
					} else {
						$value ['rowColor'] = '#FFC0C0';
						$value ['script'] = sprintf ( $this->Translate ( "Script with ID %s not exist!" ), $event->script );
					}
				} else {
					$value ['script'] = substr ( str_replace ( "\n", '', $event->script ), 0, 40 );
					if (strlen ( $value ['script'] ) == 40) $value ['script'] .= '....';
				}
			} else
				$value ['script'] = $no;
			if ($event->variableID) {
				if (IPS_VariableExists ( $event->variableID )) {
					$value ['variableID'] = $event->variableID . ' ' . $getName ( $event->variableID );
				} else {
					$value ['rowColor'] = '#FFC0C0';
					$value ['variableID'] = sprintf ( $this->Translate ( "Variable with ID %s not exist!" ), $event->variableID );
				}
			} else
				$value ['variableID'] = $no;

			$outValue = '';

			if (! is_null ( $event->startValue )) {
				if (is_bool ( $event->startValue )) $outValue = ($event->startValue ? 'true' : 'false');
				else $outValue = $event->startValue;
			}
			if (! is_null ( $event->endValue )) {
				if (is_bool ( $event->endValue )) $out = ($event->endValue ? 'true' : 'false');
				else $out = $event->endValue;
				if($outValue)$outValue.='/'.$out;
				else $outValue=$out;
			}
			
			$value ['value'] = $outValue ? $outValue : $no;

			$values [] = $value;
		}
		return $values;
	}
	// ****************** Execute ************************
	private function ExecuteEventVariable(stdClass $Event, $Value) {
		$ok = false;
		if (is_null ( $Value )) return $ok;
		if (IPS_VariableExists ( $Event->variableID )) {
			$var = IPS_GetVariable ( $Event->variableID );
			if ($var ['VariableAction'] > 9999 && IPS_InstanceExists ( $var ['VariableAction'] )) {
				$ok = RequestAction ( $Event->variableID, $Value ?? $Event->endValue);
			} elseif ($var ['VariableCustomAction'] > 9999 && IPS_ScriptExists ( $var ['VariableCustomAction'] )) {
				$ok = IPS_RunScriptEx ( $var ['VariableCustomAction'], [ 
					'VARIABLE' => $Event->variableID,
					'VALUE' => $Value ?? $Event->endValue,
					'IDENT' => $Event->ident
				] );
			} else {
				$ok = SetValue ( $Event->variableID, $Value ?? $Event->endValue);
			}
			$this->SendDebug ( __FUNCTION__, "(" . ($ok ? 'ok' : 'error') . ") with Data => " . json_encode ( [ 
				'value' => $Value ?? $Event->endValue
			] ), 0 );
		}
		return $ok;
	}
	private function ExecuteEventScript(stdClass $Event, $Value) {
		$ok = false;
		if (is_null ( $Value )) return $ok;
		if (! empty ( $Event->script )) {
			$args = [ 
				'VALUE' => $Value ?? $Event->endValue,
				'IDENT' => $Event->ident,
				'MODE' => is_null ( $Value ) ? 'end' : 'start'
			];
			$this->SendDebug ( __FUNCTION__, "With Data => " . json_encode ( $args ), 0 );
			if (is_numeric ( $Event->script )) {
				if (IPS_ScriptExists ( $Event->script )) {
					$ok = IPS_RunScriptEx ( $Event->script, $args );
				}
			} else
				$ok = IPS_RunScriptTextEx ( $Event->script, $args );
		}
		return $ok;
	}
	private function EcecuteEvent(stdClass $Event, bool $StartEvent = false) {
		$this->SendDebug ( __FUNCTION__, $this->Translate ( "Event" ) . " => " . json_encode ( $Event ), 0 );
		$ok = true;
		if (! empty ( $Event->script )) {
			$ok = $this->ExecuteEventScript ( $Event, $StartEvent ? $Event->startValue : $Event->endValue );
		}
		if (! empty ( $Event->variableID )) {
			$ok = $this->ExecuteEventVariable ( $Event, $StartEvent ? $Event->startValue : $Event->endValue );
		}
		return $ok;
	}
}
?>