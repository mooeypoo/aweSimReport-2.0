<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function linechart($ydata, $xdata='',$xmin=0,$xmax=10) {

	// Change this defines to where Your fonts are stored 
	DEFINE("TTF_DIR","/usr/X11R6/lib/X11/fonts/truetype/");
	// Change this define to a font file that You know that You have
//	DEFINE("TTF_FONTFILE","arial.ttf");

	require_once("jpgraph/jpgraph.php");
	require_once("jpgraph/jpgraph_utils.inc.php");
	require_once("jpgraph/jpgraph_line.php");	
	
	// Create the graph. These two calls are always required
	$graph = new Graph(500,400,"auto",60);

	$graph->SetScale('intlin',0,0,$xmin,$xmax);


	// Create the linear plot
	$lineplot=new LinePlot($ydata,$xdata);
	$lineplot->SetColor("blue");
	$lineplot->SetFillColor('orange@0.5');
	
	// Add the plot to the graph
	$graph->Add($lineplot);

	$graph->title->SetFont(FF_ARIAL); 
	
	return $graph; // does PHP5 return a reference automatically?
}
?> 
