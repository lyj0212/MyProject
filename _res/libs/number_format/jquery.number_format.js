/*
* @Copyright (c) 2010 Ricardo Andrietta Mendes - eng.rmendes@gmail.com
*
* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use,
* copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following
* conditions:
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
* OTHER DEALINGS IN THE SOFTWARE.
*
* How to use it:
* var formated_value = $().number_format(final_value);
*
* Advanced:
* var formated_value = $().number_format(final_value,
* 													{
* 													numberOfDecimals:3,
* 													decimalSeparator: '.',
* 													thousandSeparator: ',',
* 													symbol: 'R$'
* 													});
*/
//indica que est?sendo criado um plugin
jQuery.fn.extend({ //indica que est?sendo criado um plugin

	number_format: function(numero, params) //indica o nome do plugin que ser?criado com os parametros a serem informados
		{
		//parametros default
		var sDefaults =
			{
			numberOfDecimals: 0,
			decimalSeparator: '.',
			thousandSeparator: ',',
			symbol: ''
			}

		//fun豫o do jquery que substitui os parametros que n? foram informados pelos defaults
		var options = jQuery.extend(sDefaults, params);

		//CORPO DO PLUGIN
		var number = numero;
		var decimals = options.numberOfDecimals;
		var dec_point = options.decimalSeparator;
		var thousands_sep = options.thousandSeparator;
		var currencySymbol = options.symbol;

		var exponent = "";
		var numberstr = number.toString ();
		var eindex = numberstr.indexOf ("e");
		if (eindex > -1)
		{
		exponent = numberstr.substring (eindex);
		number = parseFloat (numberstr.substring (0, eindex));
		}

		if (decimals != null)
		{
		var temp = Math.pow (10, decimals);
		number = Math.round (number * temp) / temp;
		}
		var sign = number < 0 ? "-" : "";
		var integer = (number > 0 ?
		  Math.floor (number) : Math.abs (Math.ceil (number))).toString ();

		var fractional = number.toString ().substring (integer.length + sign.length);
		dec_point = dec_point != null ? dec_point : ".";
		fractional = decimals != null && decimals > 0 || fractional.length > 1 ?
				(dec_point + fractional.substring (1)) : "";
		if (decimals != null && decimals > 0)
		{
		for (i = fractional.length - 1, z = decimals; i < z; ++i)
		  fractional += "0";
		}

		thousands_sep = (thousands_sep != dec_point || fractional.length == 0) ?
					  thousands_sep : null;
		if (thousands_sep != null && thousands_sep != "")
		{
		for (i = integer.length - 3; i > 0; i -= 3)
		  integer = integer.substring (0 , i) + thousands_sep + integer.substring (i);
		}

		if (options.symbol == '')
		{
		return sign + integer + fractional + exponent;
		}
		else
		{
		return currencySymbol + ' ' + sign + integer + fractional + exponent;
		}
		//FIM DO CORPO DO PLUGIN

	}
});

/* 금액 한글배열 */
var nString = new Array();
nString[0] = "";
nString[1] = "일";
nString[2] = "이";
nString[3] = "삼";
nString[4] = "사";
nString[5] = "오";
nString[6] = "육";
nString[7] = "칠";
nString[8] = "팔";
nString[9] = "구";

/* 금액단위 한글배열 */
var nbString = new Array();
nbString[0] = "";
nbString[1] = "";
nbString[2] = "십";
nbString[3] = "백";
nbString[4] = "천";
nbString[5] = "만";
nbString[6] = "십";
nbString[7] = "백";
nbString[8] = "천";
nbString[9] = "억";
nbString[10] = "십";
nbString[11] = "백";
nbString[12] = "천";
nbString[13] = "조";
nbString[14] = "십";
nbString[15] = "백";
nbString[16] = "천";

function num2str(value) {
	var str  = value;
	var strCode = "";
	var codeStr = "";
	var nHan = "";
	var cnt  = 0;
	/* 천조이상이면 */
	if(str.length > 16)
	{
			//alert("한글 표현은 천조 이하에 금액까지 가능합니다.");
			return '...';
	}
	/* 뒷자리부터 루프 */
	for(var i = str.length; i > 0; i--)
	{
		/* 유니코드 구하기 */
		strCode = str.charCodeAt(i-1);
		/* 숫자가 맞다면 */
		if(strCode >= 48 && strCode <= 57)
		{
			cnt++; // 단위계산을 위해 카운팅
			codeStr = Number(String.fromCharCode(strCode)); // Number형으로
			if(codeStr != 1)
			{
				if(codeStr == 0) {
					if(cnt/5 == 1) { // 만단위표현
						nHan = nbString[5] + nHan;
					} else if(cnt/9 == 1) { // 억단위표현
						nHan = nbString[9] + nHan;
					} else if(cnt/13 == 1) { // 조단위 표현
						nHan = nbString[13] + nHan;
					}
				} else {
					/* 0이 아니면 입력값에 한글과 단위 */
					nHan = nString[codeStr] + nbString[cnt] + nHan;
				}
			} else if(codeStr == 1 && i == str.length)
			{
				/* 1이고 마지막입력값이면 한글 일 표현 */
				nHan = nString[codeStr] + nHan;
			} else {
				if(codeStr == 1 && i == 1 && (cnt == 9 || cnt == 13)) {
					/**
					* 입력값이 1이고 첫입력값이며 단위가 억이거나 조이면
					* 예) 일억 또는 일조
					* 억이하 단위에선 일을 표현안되기 때문에 일백만원을 백만원 일십만원을 십만원으로 표현되고
					* 억, 조 단위는 일억원 일조원 으로 표현하기 위해
					*/
					nHan = nString[codeStr] + nbString[cnt] + nHan;
				} else {
					nHan = nbString[cnt] + nHan;
				}
			}
			/* 단위표현에서 억만, 조억에 두번째 단위 제거 (이거 때문에 삽질했네..) */
			nHan = nHan.replace('억만','억').replace('조억','조');
		} else {
			//alert("숫자로 입력하세요.");
			//경고창 후 마지막 입력값 제거 필요 귀찮아서 안함!! ㅡㅡ;
			return '-';
		}
	}

	return nHan;
}
