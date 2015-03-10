<?php

/**
 * Copyright (C) 2009 Wskm Inc.All rights reserved.  
 * [WskmPHP] www.wskmphp.com 
 * $Id: zip.php 68 2010-09-30 09:32:29Z ws99 $ 
 */

!defined('IN_WSKM') && exit('Access Denied');

class wskm_zip
{
	static function zip($fromfile,$tofile){
		$archive = new wskm_zip();
		return $archive -> tozip(array($fromfile),$tofile);
	}

	static function unzip($fromfile,$todir=''){
		$archive = new wskm_zip();
		$re=$archive -> get_List($fromfile);
		if (empty($todir)) {
			$todir=dirname($fromfile).DS;
		}
	
		$s=$archive->Extract_File($fromfile,$todir);
		return $s[$re[0]['filename']];
	}

	function tozip($dir, $zipfilename)
	{
		if (@function_exists('gzcompress'))
		{
			$curdir = getcwd();
			if (is_array($dir))
			{
				$filelist = $dir;
			}
			else
			{
				$filelist = $this -> GetFileList($dir);
			}

			if ((!empty($dir))&&(!is_array($dir))&&(file_exists($dir))) chdir($dir);
			else chdir($curdir);

			if (count($filelist)>0)
			{
				foreach($filelist as $filename)
				{
					if (is_file($filename))
					{
						$fd = fopen ($filename, "rb");
						$content = fread ($fd, filesize ($filename));
						fclose ($fd);

						if (is_array($dir)) $filename = basename($filename);
						$this -> addFile($content, $filename);
					}
				}
				$out = $this -> file();

				chdir($curdir);
				$fp = fopen($zipfilename, "wb");
				fwrite($fp, $out, strlen($out));
				fclose($fp);
			}
			return 1;
		}
		else return 0;
	}

	function DownZipfile($dir)
	{
		if (@function_exists('gzcompress'))
		{
			$curdir = getcwd();
			if (is_array($dir))
			{
				$filelist = $dir;
			}
			else
			{
				$filelist = $this -> GetFileList($dir);
			}

			if ((!empty($dir))&&(!is_array($dir))&&(file_exists($dir))) chdir($dir);
			else chdir($curdir);

			if (count($filelist)>0)
			{
				foreach($filelist as $filename)
				{
					if (is_file($filename))
					{
						$fd = fopen ($filename, "rb");
						$content = fread ($fd, filesize ($filename));
						fclose ($fd);

						if (is_array($dir)) $filename = basename($filename);
						$this -> addFile($content, $filename);
					}
				}
				$out = $this -> file();

				chdir($curdir);

				header('Content-Encoding: none');
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment ; filename=Farticle'.date ("YmdHis",time()).'.zip');
				header('Pragma: no-cache');
				header('Expires: 0');
				print($out);

			}
			return 1;
		}
		else return 0;
	}

	function GetFileList($dir)
	{
		if (file_exists($dir))
		{
			$args = func_get_args();
			$pref = $args[1];

			$dh = opendir($dir);
			while($files = readdir($dh))
			{
				if (($files!=".")&&($files!=".."))
				{
					if (is_dir($dir.$files))
					{
						$curdir = getcwd();
						chdir($dir.$files);
						$file = array_merge($file, $this -> GetFileList("", "$pref$files/"));
						chdir($curdir);
					}
					else $file[]=$pref.$files;
				}
			}
			closedir($dh);
		}
		return $file;
	}

	var $datasec      = array();
	var $ctrl_dir     = array();
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $old_offset   = 0;

	function unix2DosTime($unixtime = 0) {
		$timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

		if ($timearray['year'] < 1980) {
			$timearray['year']    = 1980;
			$timearray['mon']     = 1;
			$timearray['mday']    = 1;
			$timearray['hours']   = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}

		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
		($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}

	function addFile($data, $name, $time = 0)
	{
		$name     = str_replace('\\', '/', $name);

		$dtime    = dechex($this->unix2DosTime($time));
		$hexdtime = '\x' . $dtime[6] . $dtime[7]
		. '\x' . $dtime[4] . $dtime[5]
		. '\x' . $dtime[2] . $dtime[3]
		. '\x' . $dtime[0] . $dtime[1];
		eval('$hexdtime = "' . $hexdtime . '";');

		$fr   = "\x50\x4b\x03\x04";
		$fr   .= "\x14\x00";
		$fr   .= "\x00\x00";
		$fr   .= "\x08\x00";
		$fr   .= $hexdtime;
		$unc_len = strlen($data);
		$crc     = crc32($data);
		$zdata   = gzcompress($data);
		$c_len   = strlen($zdata);
		$zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$fr      .= pack('V', $crc);
		$fr      .= pack('V', $c_len);
		$fr      .= pack('V', $unc_len);
		$fr      .= pack('v', strlen($name));
		$fr      .= pack('v', 0);
		$fr      .= $name;

		$fr .= $zdata;

		$fr .= pack('V', $crc);
		$fr .= pack('V', $c_len);
		$fr .= pack('V', $unc_len);

		$this -> datasec[] = $fr;
		$new_offset        = strlen(implode('', $this->datasec));

		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x14\x00";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x08\x00";
		$cdrec .= $hexdtime;
		$cdrec .= pack('V', $crc);
		$cdrec .= pack('V', $c_len);
		$cdrec .= pack('V', $unc_len);
		$cdrec .= pack('v', strlen($name) );
		$cdrec .= pack('v', 0 );
		$cdrec .= pack('v', 0 );
		$cdrec .= pack('v', 0 );
		$cdrec .= pack('v', 0 );
		$cdrec .= pack('V', 32 );

		$cdrec .= pack('V', $this -> old_offset );
		$this -> old_offset = $new_offset;

		$cdrec .= $name;

		$this -> ctrl_dir[] = $cdrec;
	}
	function file()
	{
		$data    = implode('', $this -> datasec);
		$ctrldir = implode('', $this -> ctrl_dir);

		return
		$data .
		$ctrldir .
		$this -> eof_ctrl_dir .
		pack('v', sizeof($this -> ctrl_dir)) .
		pack('v', sizeof($this -> ctrl_dir)) .
		pack('V', strlen($ctrldir)) .
		pack('V', strlen($data)) .
		"\x00\x00";
	}


	function get_List($zip_name)
	{
		$zip = @fopen($zip_name, 'rb');
		if(!$zip) return(0);
		$centd = $this->ReadCentralDir($zip,$zip_name);

		@rewind($zip);
		@fseek($zip, $centd['offset']);

		for ($i=0; $i<$centd['entries']; $i++)
		{
			$header = $this->ReadCentralFileHeaders($zip);
			
			$header['index'] = $i;$info['filename'] = $header['filename'];
			$info['stored_filename'] = $header['stored_filename'];
			$info['size'] = $header['size'];$info['compressed_size']=$header['compressed_size'];
			$info['crc'] = strtoupper(dechex( $header['crc'] ));
			$info['mtime'] = $header['mtime']; $info['comment'] = $header['comment'];
			$info['folder'] = ($header['external']==0x41FF0010||$header['external']==16)?1:0;
			$info['index'] = $header['index'];$info['status'] = $header['status'];
			$ret[]=$info; unset($header);
		}
		return $ret;
	}

	function Extract_File( $zn, $to, $index = Array(-1) )
	{
		$ok = 0; $zip = @fopen($zn,'rb');
		if(!$zip) return(-1);
		$cdir = $this->ReadCentralDir($zip,$zn);
		$pos_entry = $cdir['offset'];

		if(!is_array($index)){ $index = array($index);  }
		for($i=0; $index[$i];$i++){
			if(intval($index[$i])!=$index[$i]||$index[$i]>$cdir['entries'])
			return(-1);
		}

		for ($i=0; $i<$cdir['entries']; $i++)
		{
			@fseek($zip, $pos_entry);
			$header = $this->ReadCentralFileHeaders($zip);
			$header['index'] = $i; $pos_entry = ftell($zip);
			@rewind($zip); fseek($zip, $header['offset']);
			if(in_array("-1",$index)||in_array($i,$index))
			$stat[$header['filename']]=$this->ExtractFile($header, $to, $zip);

		}
		fclose($zip);
		return $stat;
	}

	function ReadFileHeader($zip)
	{
		$binary_data = fread($zip, 30);
		$data = unpack('vchk/vid/vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $binary_data);

		$header['filename'] = fread($zip, $data['filename_len']);
		if ($data['extra_len'] != 0) {
			$header['extra'] = fread($zip, $data['extra_len']);
		} else { $header['extra'] = ''; }

		$header['compression'] = $data['compression'];$header['size'] = $data['size'];
		$header['compressed_size'] = $data['compressed_size'];
		$header['crc'] = $data['crc']; $header['flag'] = $data['flag'];
		$header['mdate'] = $data['mdate'];$header['mtime'] = $data['mtime'];

		if ($header['mdate'] && $header['mtime']){
			$hour=($header['mtime']&0xF800)>>11;$minute=($header['mtime']&0x07E0)>>5;
			$seconde=($header['mtime']&0x001F)*2;$year=(($header['mdate']&0xFE00)>>9)+1980;
			$month=($header['mdate']&0x01E0)>>5;$day=$header['mdate']&0x001F;
			$header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
		}else{$header['mtime'] = time();}

		$header['stored_filename'] = $header['filename'];
		$header['status'] = "ok";
		return $header;
	}

	function ReadCentralFileHeaders($zip){
		$binary_data = fread($zip, 46);
		$header = unpack('vchkid/vid/vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);

		if ($header['filename_len'] != 0)
		$header['filename'] = fread($zip,$header['filename_len']);
		else $header['filename'] = '';

		if ($header['extra_len'] != 0)
		$header['extra'] = fread($zip, $header['extra_len']);
		else $header['extra'] = '';

		if ($header['comment_len'] != 0)
		$header['comment'] = fread($zip, $header['comment_len']);
		else $header['comment'] = '';

		if ($header['mdate'] && $header['mtime'])
		{
			$hour = ($header['mtime'] & 0xF800) >> 11;
			$minute = ($header['mtime'] & 0x07E0) >> 5;
			$seconde = ($header['mtime'] & 0x001F)*2;
			$year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
			$month = ($header['mdate'] & 0x01E0) >> 5;
			$day = $header['mdate'] & 0x001F;
			$header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
		} else {
			$header['mtime'] = time();
		}
		$header['stored_filename'] = $header['filename'];
		$header['status'] = 'ok';
		if (substr($header['filename'], -1) == '/')
		$header['external'] = 0x41FF0010;
		return $header;
	}

	function ReadCentralDir($zip,$zip_name)
	{
		$size = filesize($zip_name);
		if ($size < 277) $maximum_size = $size;
		else $maximum_size=277;

		@fseek($zip, $size-$maximum_size);
		$pos = ftell($zip); $bytes = 0x00000000;

		while ($pos < $size)
		{
			$byte = @fread($zip, 1); $bytes=($bytes << 8) | Ord($byte);
			if ($bytes == 0x504b0506){ $pos++; break; } $pos++;
		}

		$data=unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size',fread($zip,18));


		if ($data['comment_size'] != 0)
		$centd['comment'] = fread($zip, $data['comment_size']);
		else $centd['comment'] = ''; $centd['entries'] = $data['entries'];
		$centd['disk_entries'] = $data['disk_entries'];
		$centd['offset'] = $data['offset'];$centd['disk_start'] = $data['disk_start'];
		$centd['size'] = $data['size'];  $centd['disk'] = $data['disk'];
		return $centd;
	}

	function ExtractFile($header,$to,$zip)
	{
		$header = $this->readfileheader($zip);

		if(substr($to,-1)!="/") $to.="/";
		if(!@is_dir($to)) @mkdir($to,0777);

		$pth = explode("/",dirname($header['filename']));
		for($i=0;isset($pth[$i]);$i++){
			if(!$pth[$i]) continue;$pthss.=$pth[$i]."/";
			if(!is_dir($to.$pthss)) @mkdir($to.$pthss,0777);
		}
		if (!($header['external']==0x41FF0010)&&!($header['external']==16))
		{
			if ($header['compression']==0)
			{
				$fp = @fopen($to.$header['filename'], 'wb');
				if(!$fp) return(-1);
				$size = $header['compressed_size'];

				while ($size != 0)
				{
					$read_size = ($size < 2048 ? $size : 2048);
					$buffer = fread($zip, $read_size);
					$binary_data = pack('a'.$read_size, $buffer);
					@fwrite($fp, $binary_data, $read_size);
					$size -= $read_size;
				}
				fclose($fp);
				touch($to.$header['filename'], $header['mtime']);

			}else{
				$fp = @fopen($to.$header['filename'].'.gz','wb');
				if(!$fp) return(-1);
				$binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($header['compression']),
				Chr(0x00), time(), Chr(0x00), Chr(3));

				fwrite($fp, $binary_data, 10);
				$size = $header['compressed_size'];

				while ($size != 0)
				{
					$read_size = ($size < 1024 ? $size : 1024);
					$buffer = fread($zip, $read_size);
					$binary_data = pack('a'.$read_size, $buffer);
					@fwrite($fp, $binary_data, $read_size);
					$size -= $read_size;
				}

				$binary_data = pack('VV', $header['crc'], $header['size']);
				fwrite($fp, $binary_data,8); fclose($fp);

				$gzp = @gzopen($to.$header['filename'].'.gz','rb') or die("gzopen error!");
				if(!$gzp) return(-2);
				$fp = @fopen($to.$header['filename'],'wb');
				if(!$fp) return(-1);
				$size = $header['size'];

				while ($size != 0)
				{
					$read_size = ($size < 2048 ? $size : 2048);
					$buffer = gzread($gzp, $read_size);
					$binary_data = pack('a'.$read_size, $buffer);
					@fwrite($fp, $binary_data, $read_size);
					$size -= $read_size;
				}
				fclose($fp); gzclose($gzp);

				touch($to.$header['filename'], $header['mtime']);
				@unlink($to.$header['filename'].'.gz');

			}}
			return true;
	}

}


?>