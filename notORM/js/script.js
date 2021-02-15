var currentQuestion = 0; // index question;
var score = 0;
var totQuestions = questions.length;

var container = document.getElementById('quiz');
var questionEl = document.getElementById('question');
var opt1 = document.getElementById("opt1");
var opt2 = document.getElementById("opt2");
var opt3 = document.getElementById("opt3");
var opt4 = document.getElementById("opt4");
var nextButton = document.getElementById("nextButton");
var resultCont = document.getElementById("result");

function loadQuestion(questionIndex){

	var q = questions[questionIndex];
	questionEl.textContent = ( questionIndex + 1 ) + '. ' + q.question;
	opt1.textContent = q.option1;
	opt2.textContent = q.option2;
	opt3.textContent = q.option3;
	opt4.textContent = q.option4; 

}


function loadNextQuestion(){
	var selectedOption = document.querySelector('input[type=radio]:checked');
	if(!selectedOption){
		alert("please select your answer!");
		return;
	}
	var answer = selectedOption.value;
	if(questions[currentQuestion].answer == answer ){
		score += 10 ;
	}
	selectedOption.checked = false;
	currentQuestion++;
	if(currentQuestion == totQuestions - 1)
	{
		nextButton.textContent = "finish";
	}
	if(currentQuestion == totQuestions ){
		container.style.display = "none";
		resultCont.style.display = "block";
		var inputUser = document.getElementById('friendName').value;
		resultCont.textContent =  inputUser +  " Your Score Is: " + score + "%" ;
		document.getElementById('result2').style.display = "inline-block";
		document.getElementById('result3').style.display = "inline-block";
		return;
	}
	loadQuestion(currentQuestion);
}


	loadQuestion(currentQuestion);





