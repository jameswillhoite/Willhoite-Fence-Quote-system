function Bubble() {

    this.hidden = [];
    this.visable = [];
    this.visableSpikes = [];
    this.hiddenSpikes = [];
    var main = this;
    var addBubbleAt = 2000;
    var nextSpeedIncreaseAt = 10;
    var mainIntervalID = null;
    var nextBubbleAt = (new Date().getTime()) + addBubbleAt;
    var addSpikeAt = 4000;
    var nextSpikeAt = (new Date().getTime()) + addSpikeAt;
    var multiplySpeed = 1;


    function bubble() {
        var size = main.mainRandomInt(40, 120);
        this.img = "images/bubble.png";
        this.top = 1000;
        this.left = 0;
        this.speed = 300;
        this.width = size;
        this.height = size;
        this.clickEvent = null;
        this.id = 0;
        this.element = null;
        this.text = null;
        this.intervalID = null;
        this.points = 0;
        this.generatePoints();
    }


    bubble.prototype = {
        randomInt: function(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },
        setTextPostion: function() {
            this.text.css({
                top: (this.top + (this.height / 2) - 5) + 'px',
                left: (this.left + (this.width / 2) - 4) + 'px'
            });
        },
        inflateBubble: function() {
            var bub = this;
            bub.speed = this.randomInt(20, 100) / multiplySpeed;


            this.element.show();
            this.text.html(this.points);
            this.text.show();

            var direction = -1;

            this.intervalID = setInterval(function() {
                bub.top -= 1;
                direction = bub.randomInt(-1, 1);
                switch (direction) {
                    case -1:
                        bub.left -= 1;
                        break;
                    case 1:
                        bub.left += 1;
                        break;
                    case 0:
                        break;
                }

                bub.element.css({
                    top: bub.top + 'px',
                    left: bub.left + 'px'
                });
                bub.setTextPostion();

                if(bub.top <= 0) {
                    game.updateScore(-bub.points);
                    game.lostBubble();
                    bub.reset();
                }

            }, bub.speed);

            this.element.one('click mousedown', function() {
                game.updateScore(bub.points);
                game.gotBubble();
                bub.reset();
            });
            this.text.one('click mousedown', function() {
                game.updateScore(bub.points);
                game.gotBubble();
                bub.reset();
            });
        },
        generatePoints: function() {
            this.points = main.mainRandomInt(1,10);
        },
        reset: function() {
            var self = this;
            clearInterval(this.intervalID);
            this.element.unbind('click mousedown');
            this.text.unbind('click mousedown');
            this.element.hide();
            this.text.hide();
            this.generatePoints();
            var widthWindow = $('div#main').width()-100;
            this.top = $('div#main').height() - 100;
            this.left = this.randomInt(0, widthWindow);
            this.element.css({
                top: self.top+'px',
                left: self.left+'px'
            });
            this.setTextPostion();
            for(var a = 0; a < main.visable.length; a++) {
                if (main.visable[a].id === this.id) {
                    var temp = main.visable.splice(a, 1);
                    main.hidden.push(temp[0]);
                    break;
                }
            }
        }
    };


    function spike() {
        this.deductPoints = 0;
        this.speed = 0;
        this.img = "images/needle.png";
        this.width = 40;
        this.height = main.mainRandomInt(20, 100);
        this.top = 0;
        this.left = 0;
        this.id = 0;
        this.element = null;
        this.intervalID = null;
        this.timeOutID = null;
        this.dropSpike = null;
    }

    spike.prototype = {
        makeSpike: function() {
            var spk = this;
            this.speed = main.mainRandomInt(1, 50) * multiplySpeed;
            this.generatePointDetuction();
            this.generateSpikeDropAt();
            this.element.slideDown(500);
            this.timeOutID = setTimeout(function() {
                spk.fall();
            }, spk.dropSpike);
        },
        generatePointDetuction: function() {
            this.deductPoints = main.mainRandomInt(1,10);
        },
        generateSpikeDropAt: function() {
            this.dropSpike = main.mainRandomInt(100, 2000);
        },
        detectCollision: function() {
            var sy1 = (this.top + this.height) - 10;
            var sx1 = this.left;
            var sx2 = (this.left + this.width);


            //Look at the Visible bubbles
            for(var a = 0; a < main.visable.length; a++) {
                var bub = main.visable[a];
                var by1 = bub.top + 10;
                var bx1 = bub.left + 10;
                var bx2 = (bub.left + bub.width) - 10;

                if(sy1 >= by1) {
                    //spike is within the bubble top
                    if((sx1 >= bx1 && sx1 <= bx2) || (sx2 >= bx1 && sx2 <= bx2)) {

                        game.updateScore(-bub.points);
                        //pop the bubble
                        bub.reset();
                        game.lostBubble();
                        break;
                    }
                }
            }
        },

        resetSpike: function() {
            var widthWindow = $('div#main').width()-100;
            clearInterval(this.intervalID);
            clearTimeout(this.timeOutID);
            this.intervalID = null;
            this.timeOutID = null;
            this.element.hide();
            this.top = 0;
            this.left = main.mainRandomInt(0, widthWindow - this.width);
            this.element.css({
                top: this.top + 'px',
                left: this.left + 'px'
            });
            for(var a = 0; a < main.visableSpikes.length; a++) {
                if(main.visableSpikes[a].id = this.id) {
                    var tSpike = main.visableSpikes.splice(a,1);
                    main.hiddenSpikes.push(tSpike[0]);
                    break;
                }
            }
        },
        fall: function() {
            var heightWindow = $('div#main').innerHeight();
            var maxFall = main.mainRandomInt(this.height, heightWindow - this.height);
            var spk = this;

            this.intervalID = setInterval(function() {
                spk.top += 1;
                spk.element.css({ top: spk.top + 'px'});

                if(spk.top >= maxFall) {
                    spk.resetSpike();
                }
                spk.detectCollision();
            }, spk.speed);
        }
    };



    this.getBubble = function() {
        var bub = main.hidden.shift();
        if(bub) {
            bub.inflateBubble();
            main.visable.push(bub);
        }
        return bub;
    };

    this.stop = function() {
        clearInterval(mainIntervalID);
        mainIntervalID = null;
        for(var a = 0; a < this.visable.length; a++) {
            clearInterval(this.visable[a].intervalID);
            this.visable[a].element.unbind('click mousedown');
            this.visable[a].intervalID = null;
        }
        for(a = 0; a < this.visableSpikes.length; a++) {
            clearInterval(this.visableSpikes[a].intervalID);
            clearTimeout(this.visableSpikes[a].timeOutID);
            this.visableSpikes[a].intervalID = null;
            this.visableSpikes[a].timeOutID = null;
        }
    };
    this.end = function() {
        clearInterval(mainIntervalID);
        mainIntervalID = null;
            //get the game to stop
            while (main.visable.length > 0) {
                for (var a = 0; a < main.visable.length; a++) {
                    main.visable[a].reset();
                }
                for (a = 0; a < main.visableSpikes.length; a++) {
                    main.visableSpikes[a].resetSpike();
                }
            }
            while(main.visableSpikes.length > 0) {
                for (var a = 0; a < main.visable.length; a++) {
                    main.visable[a].reset();
                }
                for (a = 0; a < main.visableSpikes.length; a++) {
                    main.visableSpikes[a].resetSpike();
                }
            }
    };

    this.start = function() {
        if(this.visable.length > 0) {
            for (var a = 0; a < this.visable.length; a++) {
                this.visable[a].inflateBubble();
            }
        }

        mainIntervalID = setInterval(function() {
            var curTime = (new Date()).getTime();
            if(curTime >= nextBubbleAt) {
                main.getBubble();
                nextBubbleAt = curTime + addBubbleAt;
                if(game.score > 0 && game.score >= nextSpeedIncreaseAt && addBubbleAt > 400) {
                    addBubbleAt -= 50;
                    nextSpeedIncreaseAt += 10;
                }
            }
            if(curTime >= nextSpikeAt) {
                main.getSpike();
                nextSpikeAt = curTime + addSpikeAt;
            }
        }, 1);


    };

    this.mainRandomInt = function(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    for(var a = 0; a < 100; a++) {
        var widthWindow = $('div#main').innerWidth() - 100;
        var heightWindow = $('div#main').innerHeight() - 100;
        var body = jQuery('div#main');
        var bub = new bubble();
        bub.id = "bubble" + a;
        bub.top = heightWindow;
        bub.left = bub.randomInt(0, widthWindow-bub.width);
        body.append('<img id="' + bub.id + '" src="' + bub.img + '" style="top: '+bub.top+'px; left: ' + bub.left+'px; width: '+bub.width+'px; height: '+bub.height+'px; display: none;">');
        bub.element = jQuery('img#'+bub.id);
        body.append('<span id="'+bub.id+'Text" class="bubbleText"></span>');
        bub.text = jQuery('span#'+bub.id+'Text');
        bub.setTextPostion();
        main.hidden.push(bub);

        var spk = new spike();
        spk.id = "spike"+a;
        spk.top = 0;
        spk.left = main.mainRandomInt(10, widthWindow-spk.width);
        body.append('<img id="' + spk.id + '" src="' + spk.img + '" style="top: ' + spk.top + 'px; left: ' + spk.left + 'px; width: ' + spk.width + 'px; height: ' + spk.height +'px; display: none;">');
        spk.element = $('img#'+spk.id);
        main.hiddenSpikes.push(spk);
    }



    this.getSpike = function() {
        var tSpike = main.hiddenSpikes.shift();
        if(tSpike) {
            tSpike.makeSpike();
            main.visableSpikes.push(tSpike);
        }
        return tSpike;

    };
    
    this.setSkillLevel = function(level) {
        switch(level) {
            case "beginner":
                addBubbleAt = 2000;
                addSpikeAt = 8000;
                multiplySpeed = 1;
                break;
            case "intermediate":
                addBubbleAt = 1500;
                addSpikeAt = 4000;
                multiplySpeed = 1.5;

                break;
            case "hard":
                addBubbleAt = 1000;
                addSpikeAt = 2000;
                multiplySpeed = 1.2;
                break;
            case "insane":
                addBubbleAt = 500;
                addSpikeAt = 500;
                multiplySpeed = 2;
                break;
        }
    };



}

var game = {
    score: 0,
    bubblesPopped: 0,
    bubblesLost: 0,
    Bubble: null,

    init: function() {
        var self = this;
        var height = $(document).innerHeight();
        var width = $(document).innerWidth();
        jQuery('body div.container-fluid').css({
            height: height,
            width: width
        });
        /*
        Initialize the Bubble Class
         */
        this.Bubble = new Bubble();

        //Place the score board;
        $('div#scoreBoard').css({
            top: "5px",
            left: (width - 155) + 'px'
        });
        /*
        Set the score to zero
         */
        this.updateScore(0);

        /*
        Listen for High score form submit
         */
        $('button#submitScore').on('click', function() {
            var name = $('div#gameOverModal form input#name');
            if(name.val().length === 0) {
                name.addClass('is-invalid');
            }
            else {
                name.removeClass('is-invalid');
            }
            self.saveScore(name.val(), String(self.bubblesPopped), String(self.bubblesLost), String(self.score));

        });
        $('div#gameOverModal form').on('submit', function(e) {
            e.preventDefault();

            var name = $('div#gameOverModal form input#name');
            if(name.val().length === 0) {
                name.addClass('is-invalid');
            }
            else {
                name.removeClass('is-invalid');
            }
            self.saveScore(name.val(), String(self.bubblesPopped), String(self.bubblesLost), String(self.score));
        });

        /*
        Override the mouse drag even
         */
        $('body').on('drag contextmenu', function(e) {
            e.preventDefault();
        });

    },
    randomNumber: function(min, max) {
        if(!min)
            min = 0.1;
        if(!max)
            max = 10;
        var minNumber = min;
        var maxNumber = max;
        return Math.random() * (maxNumber - minNumber) + minNumber;
    },
    randomInt: function(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },

    updateScore: function(point) {
        this.score += parseInt(point);
        var scoreBoard = $('span#score');
        scoreBoard.html(this.score);

    },
    lPad: function(num) {
        var newNum = String(num);
        while(newNum.length < 2) {
            newNum = "0" + newNum;
        }
        return newNum;
    },
    timeInterval: null,
    setSkillLevel: function(level) {
        var self = this;
        this.score = 0;
        this.bubblesLost = 0;
        this.bubblesPopped = 0;
        this.updateScore(0);
        this.Bubble.setSkillLevel(level);
        jQuery('div#howToPlay').hide();
        jQuery('div#scoreBoard').show();
        this.Bubble.start();
        var time = new Date((new Date()).getTime() + 120000); //120000
        this.timeInterval = setInterval(function() {
            var now = (new Date()).getTime();
            var timeLeft = new Date(time - now);
            jQuery('span#time').html(self.lPad(timeLeft.getMinutes()) + ':' + self.lPad(timeLeft.getSeconds()));
            if((now+1000) >= time.getTime()) {
                clearInterval(self.timeInterval);
                self.Bubble.end();
                jQuery('div#howToPlay').fadeIn(500);
                jQuery('div#scoreBoard').fadeOut(500);
                self.showScore();
            }
        }, 1000);

    },

    gotBubble: function() {
        this.bubblesPopped++;
    },
    lostBubble: function() {
        this.bubblesLost++;
    },

    showScore: function() {
        var modal = $('div#gameOverModal');
        modal.find('span#bubblesLost').html(this.bubblesLost);
        modal.find('span#bubblesPopped').html(this.bubblesPopped);
        modal.find('span#score').html(this.score);
        var highScore = false;
        for(var a = 0; a < this.stats.length; a++) {
            if(this.score > this.stats[a].score) {
                highScore = true;
                break;
            }
        }
        if(this.stats.length < 5)
            highScore = true;

        console.log("HighScore: " + highScore);

        if(highScore) {
            modal.find('div#enterName').show();
            modal.find('button#submitScore').show();
        }
        else {
            modal.find('div#enterName').hide();
            modal.find('button#submitScore').hide();
        }

        modal.modal('show');

    },
    stats: [],
    saveScore: function(name, bubblesPopped, bubblesLost, score) {
        var statsBoard = $('table#statsBoard tbody');
        var modal = $('div#gameOverModal');
        var self = this;

        jQuery.ajax({
            type: "POST",
            url: window.baseURL + "controller.php?task=saveGame",
            data: {name: name, bubblesLost: bubblesLost, bubblesPopped: bubblesPopped, score: score},
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                console.log(data);
                if(data.error) {
                    console.log(data.error_msg);
                    return;
                }
                var temp = {id: null, name: name, bubblesPopped: bubblesPopped, bubblesLost: bubblesLost, score: score};

                self.stats.push(temp);

                self.stats.sort(function(a,b) {
                    if(parseInt(a.score) > parseInt(b.score)) return -1;
                    if(parseInt(a.score) < parseInt(b.score)) return 1;
                    return 0;
                });
                var html = '';
                var max = (self.stats.length > 5) ? 5 : self.stats.length;
                for(var a = 0; a < max; a++) {
                    html += '<tr><td>' + self.stats[a].name + '</td><td>'+self.stats[a].bubblesPopped+'</td><td>'+self.stats[a].bubblesLost+'</td><td>'+self.stats[a].score+'</td></tr>';
                }
                statsBoard.empty().html(html);
                modal.find('input#name').val('');
                modal.modal('hide');
                self.replaceID = null;


            },
            error: function(a,b,c) {
                console.log(a,b,c);
            }
        });
    }

};