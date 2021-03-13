<?
	class Mime
	{
		var $count = 0;  // Extra check to ensure that all boundaries/cids are different

		var $from;
		var $to;
		var $cc;
		var $subject;

		var $headers;
		var $body;
		
		var $bounds = array();
	
		function Mime($from, $to, $cc, $subject, $type)
		{
			$this->from = $from;
			$this->to = $to;
			$this->cc = $cc;
			$this->subject = $subject;

			if(substr($type, 0, 9) == "multipart") {
				$b = $this->generate_boundary();
				array_push($this->bounds, $b);
				$ct_extra = "; boundary=\"$b\"";
			} else $ct_extra = "";

			$this->headers = "Date: " . date("r") . "\n" .
		   			   		 "From: $from\n" .
					   		 "Cc: $cc\n" .
		   			   		 "MIME-Version: 1.0\n" .
		               		 "Content-Type: $type$ct_extra";
		}

		function send()
		{
			// Close any open multiparts
			while($b = array_pop($this->bounds)) $this->body .= "\n--$b--\n";

			mail($this->to, $this->subject, $this->body, $this->headers);
		}

		function generate_boundary()
		{
			$boundary = str_replace(" ", "-", microtime()) . "-" . mt_rand() . "-" . mt_rand() . "-" . $this->count . "=_";
			$this->count++;
			return $boundary;
		}

		function generate_cid($domain = "business1st.uk.com")
		{
			$cid = str_replace(" ", "-", microtime()) . "-" . mt_rand() . "-" . $this->count . "@$domain";
			$this->count++;
			return $cid;
		}

		function start_multipart($type)
		{
			$this->insert_boundary();
			$b = $this->generate_boundary();
			array_push($this->bounds, $b);
			$this->body .= "Content-Type: multipart/$type; boundary=\"$b\"\n\n";
		}

		function end_multipart()
		{
			$b = array_pop($this->bounds);
			$this->body .= "\n--$b--\n";
		}

		function insert_text($type, $text, $charset = "iso-8859-1")
		{
			$this->insert_boundary();
			$this->body .= "Content-Type: text/$type; charset=$charset\n\n$text";
		}

		function insert_image($filename, $id)
		{
			$type = substr($filename, strrpos($filename, ".") + 1);
			if($type == "jpg") $type = "jpeg";

			$fp = fopen($filename, "r");
			$contents = fread($fp, filesize($filename));
			fclose($fp);
	
			$this->insert_boundary();
			$this->body .= "Content-Type: image/$type\n" .
					 		"Content-Transfer-Encoding: BASE64\n" .
					 		"Content-ID: $id\n\n" .
							base64_encode($contents);
		}
		
		function insert_attachment($type, $filename)
		{
			$fp = fopen($filename, "r");
			$contents = fread($fp, filesize($filename));
			fclose($fp);

			$this->insert_boundary();	
			$this->body .= "Content-Type: $type\n" .
							"Content-Transfer-Encoding: BASE64\n" .
							"Content-Disposition: attachment; filename=" .
							basename($filename) . "\n\n" .
							base64_encode($contents);
		}

		function insert_boundary()
		{
			if($b = end($this->bounds)) $this->body .= "\n--$b\n";
		}
	}
?>