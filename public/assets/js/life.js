/**
 * [JS Desc. text goes here...]
 * @author dbroadaw
 */
jQuery(document).ready(function ($){
    // DOM
    var $genesis              = $('a.golGenesis');
    var $apocalypse           = $('a.golApocalypse');
    var $reload               = $('a.golReload');
    var $golContainer         = $('div.gol');
    var $golGenerationDisplay = $('span.golGenerationDisplay');
    var $golWidthDisplay      = $('span.golWidthDislplay');
    var $golHeightDisplay     = $('span.golHeightDisplay');
    var $golAllCells          = $('div.cell');

    // API
    var apiURL        = 'http://your.mom/api/v1/getLife';

    // Life
    var golGeneration;
    var golWidth;
    var golHeight;
    var golActive;

    $genesis.on('click', function() {
        $apocalypse.removeClass('disabled');
        $genesis.addClass('disabled');
        getLife();
    });

    $apocalypse.on('click', function () {
        $golContainer.data('active', 0);
        $reload.removeClass('disabled');
    });

    $reload.on('click', function () {
        window.location.reload(true);
    });

    function getLife() {
        //  Prevents multiple calls
        if ($.active > 1) {
            return;
        }

        golActive = $golContainer.data('active');
        if (golActive == 0) {
            return;
        }

        golGeneration = $golContainer.data('generation');
        golWidth      = $golContainer.data('width');
        golHeight     = $golContainer.data('height');


        var $liveCells = null;
        if (golGeneration > 0) {
            $liveCells = getCurrentGeneration();
        }

        $.ajax({
            url: apiURL,
            type: 'post',
            data: {
                generation: golGeneration,
                width: golWidth,
                height: golHeight,
                currentGeneration: $liveCells
            },

            success: function (response) {
                $golGenerationDisplay.text(response.data.generation);
                $golWidthDisplay.text(response.data.width);
                $golHeightDisplay.text(response.data.height);

                $golContainer.data('width', response.data.width);
                $golContainer.data('height', response.data.height);
                $golContainer.data('generation', response.data.generation);

                if (response.data.generation == 1) {
                    renderGrid(response.data.width, response.data.height);
                    renderLife(response.data.life.life);

                } else {
                    resetGrid();
                    renderLife(response.data.life);
                }

                getLife();
            },

            error: function (response) {
                console.log('ERROR --|');
                console.log(response);
            }
               });
    }

    function getCurrentGeneration() {
        var $liveCells          = $('div.life');
        var liveCellCoordinates = [];

        $liveCells.each(function (index, value) {
            liveCellCoordinates.push(
                {
                    row:    $(this).data('row'),
                    column: $(this).data('col')
                }
            );
        });

        return liveCellCoordinates;
    }

    function renderLife(lifeData) {
        if (lifeData.length == 0) {
            console.log('EVERYBODY DIED!');
            return;
        }

        $(lifeData).each (function (index, value) {
            if (value.data == 1) {
                var $lifeCell = $('div.cell[data-row="' + value.row + '"][data-col="' + value.column + '"]');

                //$lifeCell.css('background-color', '#'+Math.floor(Math.random()*16777215).toString(16));

                $lifeCell.addClass('life');
                $lifeCell.data('life', 1);
            }
        });
        var $color = $('.life');
        $color.css('background-color', '#'+Math.floor(Math.random()*16777215).toString(16));
    }

    function renderGrid(width, height) {
        $golContainer.empty();

        var r = 0;
        //  < insteasd of <= to account for zero indexing
        while (r < height) {
            // r = row = height
            var $row = $('<div class="cell-row"></div>');

            // c = column = width
            var c = 0;
            //  < insteasd of <= to account for zero indexing
            while (c < width) {
                $row.append('<div class="cell" data-row="' + r + '" data-col="' + c + '" data-life="0"></div>');
                c++;
            }
            $golContainer.append($row);
            r++;
        }
    }

    function resetGrid() {
        var $liveCells = $golContainer.find('div.life');
        $liveCells.each(function (index, value) {
            $(this).removeClass('life');
            $(this).css('background-color', '#ccc');
        });
    }
});