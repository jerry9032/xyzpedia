$( document ).tooltip({
	show: {
		duration: "fast"
	},
	hide: {
		effect: "hide"
	}
});

function word_count(area, word, part) {
	str = "";
	str = jQuery("#"+area).val();
	str = str.replace(/\s*$|^\s*/g,"");
	str = str.replace(/[\r]/g,"").replace(/[\n]/g,"");
	n = str.length;
	jQuery("#"+word).html(n);
	jQuery("#"+part).html(parts(n));
}
function parts(len) {
	if (len <= 0) return 0;
	if (len > 0 && len <= 337) return 1;
	if (len > 337 && len <= 685) return 2;
	if (len > 685 && len <= 1033) return 3;
	if (len > 1033 && len <= 1370) return 4;
	if (len > 1370 && len <= 1718) return 5;
	if (len > 1718 && len <= 2066) return 6;
	if (len > 2066 && len <= 2403) return 7;
	if (len > 2403 && len <= 2751) return 8;
	if (len > 2751 && len <= 3099) return 9;
	if (len > 3099) return 10;
}