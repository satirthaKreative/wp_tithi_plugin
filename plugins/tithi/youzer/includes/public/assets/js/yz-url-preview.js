/**
 * Copyright (c) 2015 Leonardo Cardoso (http://leocardz.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.0
 */
( function( $ ) {

    'use strict';

    var yz_wall_app = angular.module( 'YouzerWallApp', [] );
    yz_wall_app.controller('YouzerWallController', ['$scope', function ($scope) {}]);
    yz_wall_app.directive('linkPreview', ['$compile', '$http', '$sce', function ($compile, $http, $sce) {
        var yz_lp_folder = Youzer.youzer_url + 'includes/public/core/functions/live-preview/'; 

        var URL_REGEX = /((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_\-~@\(\)\%]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&#%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?/i;

        var trim = function (str) {
            return str.replace(/\s+/g, ' ').trim();
        };

        var hasUrl = function ($text) {
            return URL_REGEX.test($text);
        };

        var linker = function (scope, element, attrs) {

            $( '.lp-preview-no-thubmnail-text input' ).on( 'change', function() {
                if ( this.checked) {
                    $('#yz-wall-form .lp-prepost' ).addClass( 'yz-lp-no-thumbnail' );
                } else {
                    $('#yz-wall-form .lp-prepost' ).removeClass( 'yz-lp-no-thumbnail' );
                }
            });

            $( '.yz-wall-textarea' ).bind({  // Main textarea
                paste: function () {
                    setTimeout(function () {
                            scope.textCrawling( $( '.yz-wall-textarea').val(), scope, element, $compile);
                    }, 100);
                },
                keyup: function (e) {
                    if ((e.which == 13 || e.which == 32 || e.which == 17)) {
                            scope.textCrawling( $( '.yz-wall-textarea' ).val(), scope, element, $compile);
                    }
                }
            });

            if ( $( '.yz-wall-textarea' ).get(0).emojioneArea ) {
                $( '.yz-wall-textarea' ).get(0).emojioneArea.on("paste", function(btn, event) {
                    setTimeout(function () {
                        scope.textCrawling( $( '.yz-wall-textarea' ).get(0).emojioneArea.getText(), scope, element, $compile);
                    }, 100 );
                });

                $( '.yz-wall-textarea' ).get(0).emojioneArea.on("keyup", function(btn, e) {
                    if ( ( e.which == 13 || e.which == 32 || e.which == 17 ) ) {
                        scope.textCrawling( $( '.yz-wall-textarea' ).get(0).emojioneArea.getText(), scope, element, $compile);
                    }
                });
            }

            $(element.find('input')[0]).bind({ // Preview title
                keyup: function (e) {
                    if (e.which == 13) {
                        scope.previewTitleEditing = false;
                        $compile(element.find('input')[0])(scope);
                    }
                }
            });

            $(element.find('textarea')[1]).bind({ // Preview description
                keyup: function (e) {
                    if (e.which == 13) {
                        scope.previewDescriptionEditing = false;
                        $compile(element.find('textarea')[1])(scope);
                    }
                }
            });

            scope.$watchGroup(
                ['hideLoading', 'hidePreview', 'allowPosting', 'preview', 'isFetching', 'posts', 'noThumbnail', 'noImage'],
                function (newValues, oldValues, scope) {
                    scope.hideLoading = newValues[0];
                    scope.hidePreview = newValues[1];
                    scope.allowPosting = newValues[2];
                    scope.preview = newValues[3];
                    scope.isFetching = newValues[4];
                    scope.posts = newValues[5];
                    scope.noThumbnail = newValues[6];
                    scope.noImage = newValues[7];
                });

        };

        var defaultValues = function ($scope) {

            $scope.currentImageIndex = 1;

            $scope.preview = {
                "id": -1,
                "text": "",
                "title": "",
                "url": "",
                "pageUrl": "",
                "canonicalUrl": "",
                "description": "",
                "image": "",
                "images": [],
                "video": "",
                "videoIframe": ""
            };

            $scope.showIframe = false;

            $scope.hidePreview = true;

            $scope.hideLoading = true;

            $scope.isFetching = false;

            $scope.allowPosting = false;

            $scope.rightArrowDisabled = true;

            $scope.leftArrowDisabled = true;

            $scope.noThumbnail = false;

            $scope.noImage = false;

            $scope.previewTitleEditing = false;

            $scope.previewDescriptionEditing = false;

            $scope.imageAmount = angular.isDefined($scope.imageAmount) ? $scope.imageAmount : -1;
            $scope.cancelButtonClass = angular.isDefined($scope.cancelButtonClass) ? $scope.cancelButtonClass : 'danger';
            $scope.loadingText = angular.isDefined($scope.loadingText) ? $scope.loadingText : YouzerLP.loading;
            $scope.loadingImage = angular.isDefined($scope.loadingImage) ? $scope.loadingImage : yz_lp_folder + 'img/empty.png';
            $scope.thubmnailText = angular.isDefined($scope.thubmnailText) ? $scope.thubmnailText : YouzerLP.choose_thumnnail;
            $scope.noThubmnailText = angular.isDefined($scope.noThubmnailText) ? $scope.noThubmnailText : YouzerLP.no_thumbnail;
            $scope.thumbnailPagination = angular.isDefined($scope.thumbnailPagination) ? $scope.thumbnailPagination : '%N of %N';
            $scope.defaultTitle = angular.isDefined($scope.defaultTitle) ? $scope.defaultTitle : YouzerLP.enter_title;
            $scope.defaultDescription = angular.isDefined($scope.defaultDescription) ? $scope.defaultDescription : YouzerLP.enter_desc;
        };

        return {
            restrict: 'E',
            scope: {
                placeholder: '@placeholder',
                imageAmount: '@iamount',
                buttonClass: '@bclass',
                buttonText: '@btext',
                cancelButtonClass: '@cbclass',
                cancelButtonText: '@cbtext',
                loadingText: '@ltext',
                loadingImage: '@limage',
                thubmnailText: '@ttext',
                noThubmnailText: '@nttext',
                thumbnailPagination: '@tpage',
                defaultTitle: '@dtitle',
                defaultDescription: '@ddescription'
            },
            link: linker,
            controller: function ($scope) {

                $scope.posts = [];

                defaultValues($scope);

                $scope.textCrawling = function ($text, scope, element, $compile) {
                    if (!$scope.isFetching && $text !== "") {
                        if (hasUrl($text)) {
                            $scope.hidePreview = true;
                            $scope.hideLoading = false;
                            $scope.isFetching = true;
                            $scope.allowPosting = false;

                            var url = yz_lp_folder + 'textCrawler.php';
                            var jsonData = angular.toJson({
                                text: $text,
                                imageAmount: $scope.imageAmount
                            });

                            $http({
                                url: url,
                                method: "POST",
                                data: "data=" + window.btoa(encodeURIComponent(jsonData)),
                                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                            }).success(function (data, status, headers, config) {

                                $scope.preview = data;

                                if ( $scope.preview.video ) {
                                    $scope.videoIframeHTML = $sce.trustAsHtml(data.videoIframe);
                                }

                                $scope.hasEmptyInfo( $scope );

                                $scope.hidePreview = false;

                                $scope.hideLoading = true;

                                $scope.allowPosting = true;

                                if ( $scope.preview.image ) {
                                    $scope.enablePagination($scope);
                                    $scope.updatePagination($scope);
                                }

                            });

                        }
                    }
                };

                $scope.hasEmptyInfo = function ($scope) {
                    if ($scope.preview.title == "") {
                        $scope.preview.title = $scope.defaultTitle;
                    }
                    if ($scope.preview.description == "") {
                        $scope.preview.description = $scope.defaultDescription;
                    }
                };

                $scope.enablePagination = function ($scope) {
                    var hasPreviewImage = $scope.preview.image === "" || $scope.preview.image === null;

                    $scope.noThumbnail = hasPreviewImage;
                    $scope.noImage = hasPreviewImage;

                    $scope.leftArrowDisabled = $scope.preview.images.length == 1;
                    $scope.rightArrowDisabled = $scope.preview.images.length == 1;
                };

                $scope.updatePagination = function ($scope) {
                    var pagination = $scope.thumbnailPagination;
                    pagination = pagination.replace("%N", $scope.currentImageIndex);
                    pagination = pagination.replace("%N", $scope.preview.images.length);
                    $scope.thumbnailPaginationText = pagination;

                    $scope.leftArrowDisabled = $scope.currentImageIndex == 1;
                    $scope.rightArrowDisabled = $scope.currentImageIndex == $scope.preview.images.length;
                };

                $scope.previousImage = function () {
                    if ($scope.currentImageIndex != 1) {
                        $scope.currentImageIndex--;
                        $scope.setNewPreviewImage($scope.currentImageIndex);
                    }
                };

                $scope.nextImage = function () {
                    if ($scope.currentImageIndex != $scope.preview.images.length) {
                        $scope.currentImageIndex++;
                        $scope.setNewPreviewImage($scope.currentImageIndex);
                    }
                };

                $scope.setNewPreviewImage = function ($index) {
                    $scope.preview.image = $scope.preview.images[$index - 1];
                    $scope.updatePagination($scope, $index);
                };

                $scope.editPreviewTitle = function () {
                    $scope.previewTitleEditing = true;
                };

                $scope.editPreviewDescription = function () {
                    $scope.previewDescriptionEditing = true;
                };

                $scope.resetPreview = function () {
                    defaultValues($scope);
                };

                $scope.imageAction = function (post) {

                    if (post.video == false) {
                        window.open(post.pageUrl, '_blank');
                    } else {
                        $scope.showIframe = true;
                    }

                };

                $scope.hidePlay = function (post) {
                    return post.video == false || $scope.showIframe == true;
                };

                $scope.layoutWithoutImage = function (post) {
                    return post.image == '' || $scope.showIframe == true;
                };

                $scope.layoutWithImage = function (post) {
                    return post.image != '' || (post.video == true && $scope.showIframe == false);
                };

            },
            templateUrl: function (elem, attrs) {
                return yz_lp_folder + 'template.html'
            }
        };
    }]);

})( jQuery );