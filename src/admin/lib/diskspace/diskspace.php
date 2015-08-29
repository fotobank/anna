<?
#####################################################################
#  DiskSpace 0,23
#  Released under the terms of the GNU General Public License.
#  Please refer to the README file for more information.
#####################################################################

#####################################################################
# PLEASE EDIT THE FOLLOWING VARIABLES:
#####################################################################

# Please choose a language file:
$languageFile = "./language_files/russian.php";

# Your home directory on the server. Check the README file if you
# don't know how to find your home directory.
$user_home = "/";

# The available hard disk space for your user on the server --
# measured in megabytes.
$available_space = "2000";

#####################################################################
# THAT'S IT! NO MORE EDITING NECESSARY.
#####################################################################



require($languageFile);

exec("du -s $user_home", $du);	# This will output something like "3383 $user_home"
$brugtplads = explode(" ", $du[0]);

$brugtplads = round($brugtplads[0] / 1024, 2);
$pladstilbage = $available_space - $brugtplads;

$p = $available_space / 100;
$p_brugtplads = round($brugtplads / $p);
$p_pladstilbage = round($pladstilbage / $p);

$bredde_brugt = $p_brugtplads * 3;	# This is used as the "width" attribute in the "<img>" tag.
$bredde_tilbage = $p_pladstilbage * 3;	# This is used as the "width" attribute in the "<img>" tag.

/**
 * @param $tal
 *
 * @return array|string
 */
function afrunding($tal) {
	if (preg_match("/\./", $tal)) {					# Example: $tal = 12.2547821
		$tal = explode("\.", $tal);			# $tal[0] = 12 and $tal[1] = 2547821
		$tal[1] = substr($tal[1], 0, 2);		# $tal[1] = 25
		$ciffer1 = substr($tal[1], 0, 1);		# $ciffer1 = 2
		$ciffer2 = substr($tal[1], 1, 2);		# $ciffer2 = 5
		if ($ciffer2 >= 5) ++$ciffer1;	# $ciffer1 now becomes 3
		$tal = $tal[0].$ciffer1;			# $tal is now 12.3
	}
	return $tal;
}




	echo "<h2>$l3</h2>\n";
	echo "<img src=image.gif height=20 width=$bredde_brugt alt=\"$l4: $p_brugtplads$l7\">";
	echo "<img src=image_gray.gif height=20 width=$bredde_tilbage alt=\"$l5: $p_pladstilbage$l7\"><p>\n";

	echo "<img src=image.gif height=20 width=10 alt=\"$l6\"><img src=image_gray.gif height=20 width=10 alt=\"$l6\">\n";
	echo "$l6: $available_space $l8 (100$l7)<p>\n";
	echo "<img src=image.gif height=20 width=20 alt=\"$l4\">\n";
	echo "$l4: ".afrunding($brugtplads)." $l8 ($p_brugtplads$l7)<p>\n";
	echo "<img src=image_gray.gif height=20 width=20 alt=\"$l5\">\n";
	echo "$l5: ".afrunding($pladstilbage)." $l8 ($p_pladstilbage$l7)\n";

