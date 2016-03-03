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
    var $golLivingCellsDisplay= $('span.golLivingCellsDisplay');
    var $golWidthDisplay      = $('span.golWidthDislplay');
    var $golHeightDisplay     = $('span.golHeightDisplay');
    var $golAlert             = $('div.golAlert');

    // API
    var apiURL        = 'http://your.mom/api/v1/getLife';

    // Life
    var golGeneration;
    var golWidth;
    var golHeight;
    var golActive;

    //  Button Controls
    $genesis.on('click', function() {
        $apocalypse.removeClass('disabled');
        $genesis.addClass('disabled');
        updateAlert('success', 'Life has begun.');
        $golContainer.data('active', 1);
        getLife();
    });

    $apocalypse.on('click', function () {
        $golContainer.data('active', 0);
        $genesis.removeClass('disabled');
    });

    $reload.on('click', function () {
        window.location.reload(true);
    });

    //  Render Grid
    renderGrid($golContainer.data('width'), $golContainer.data('height'));

    /**
     *
     */
    function getLife() {
        //  Prevents multiple calls
        if ($.active > 1) {
            return;
        }

        golActive = $golContainer.data('active');
        if (golActive == 0) {
            updateAlert('info', 'Life has been paused');
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

    /**
     *
     * @returns {Array}
     */
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

    /**
     *
     * @param lifeData
     */
    function renderLife(lifeData) {
        if (lifeData == null || lifeData.length == 0) {
            console.log('EVERYBODY DIED!');
            updateAlert('danger', 'Evolution has failed.');
            $golContainer.data('active', 0);
            return;
        }

        //  Tell user how much life they have created.
        //  TODO: Prevent God complexes

        $golLivingCellsDisplay.text(lifeData.length);

        $(lifeData).each (function (index, value) {
            if (value.data == 1) {
                var $lifeCell = $('div.cell[data-row="' + value.row + '"][data-col="' + value.column + '"]');

                $lifeCell.addClass('life');
                $lifeCell.data('life', 1);
            }
        });
        var $color = $('.life');
        $color.css('background-color', '#'+Math.floor(Math.random()*16777215).toString(16));
    }

    /**
     *
     * @param width
     * @param height
     */
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

    /**
     *
     */
    function resetGrid() {
        var $liveCells = $golContainer.find('div.life');
        $liveCells.each(function (index, value) {
            $(this).removeClass('life');
            $(this).css('background-color', '#ccc');
        });
    }

    /**
     *
     * @param status
     * @param message
     */
    function updateAlert(status, message) {
        $golAlert.addClass('alert-' + status);
        $golAlert.text(message);
        $golAlert.toggle();
        $golAlert.fadeOut(5000);
    }
});
