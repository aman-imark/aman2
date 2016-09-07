/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Imtech = {};
Imtech.Pager = function() {
    this.paragraphsPerPage = 3;
    this.currentPage = 1;
    this.pagingControlsContainer = '#pagingControls';
    this.pagingContainerPath = '#camps';

    this.numPages = function() {
        var numPages = 0;
        if (this.paragraphs != null && this.paragraphsPerPage != null) {
            numPages = Math.ceil(this.paragraphs.length / this.paragraphsPerPage);
        }
        
        return numPages;
    };

    this.showPage = function(page) {
        this.currentPage = page;
        var html = '';

        this.paragraphs.slice((page-1) * this.paragraphsPerPage,
            ((page-1)*this.paragraphsPerPage) + this.paragraphsPerPage).each(function() {
            html += '<div>' + $(this).html() + '</div>';
        });

        $(this.pagingContainerPath).html(html);

        renderControls(this.pagingControlsContainer, this.currentPage, this.numPages());
    }

    var renderControls = function(container, currentPage, numPages) {
        var pagingControls = '<ul class=pagination>';
        if(numPages > 1)
        {
            if (currentPage > 1 && numPages != 0) {
                var j = currentPage - 1;
                pagingControls += '<li><a class="page-numbers" href="#" onclick="pager.showPage(' + j + '); return false;">prev</a></li>';
              }
              
            for (var i = Math.max(currentPage-3, 1); i <= Math.max(1, Math.min(numPages,currentPage+3)); i++) {
                
            if (i != currentPage) {
                pagingControls += '<li><a class="page-numbers" href="#" onclick="pager.showPage(' + i + '); return false;">' + i + '</a></li>';
            } else {
                pagingControls += '<li><span class="page-numbers current">' + i + '</span></li>';
            }
        }
        if (currentPage < numPages && numPages != 0) {
                var j = currentPage + 1;
                pagingControls += '<li><a class="page-numbers" href="#" onclick="pager.showPage(' + j + '); return false;">next</a></li>';
              }

        
        }
        pagingControls += '</ul>';

        $(container).html(pagingControls);
    }
}
