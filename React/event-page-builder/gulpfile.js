const gulp = require('gulp');
const inlinesource = require('gulp-inline-source');
const replace = require('gulp-replace');
const rename = require('gulp-rename');


let styleLinkLine = '';

const phpHeader =
    '<?php\n' +
    '/* @var $block Magento\\Framework\\View\\Element\\Template */\n' +
    '$event_id = $block->getData(\'event_id\');\n' +
    '?>';
const dataEventId = '<?= $event_id ?>';

gulp.task('replaceJs', () => {
    return gulp
        .src('./build/*.html')
        .pipe(replace('.js"></script>', '.js" inline></script>'))
        .pipe(gulp.dest('./build'));
})

gulp.task('removeCss', () => {
    return gulp
        .src('./build/*.html')
        .pipe(
            replace(/(<link\s+href="[^"]+"\s+rel="stylesheet">)/g,
                function (match, p1) {
                    // Save the matched content to the variable
                    styleLinkLine += '\n' + p1;

                    // Replace the matched content with the desired string
                    return '';
                }))
        .pipe(gulp.dest('./build'));
})

gulp.task('replaceCss', () => {
    return gulp
        .src('./build/*.html')
        .pipe(replace('<style rel="stylesheet"></style>', styleLinkLine))
        .pipe(replace('rel="stylesheet">', 'rel="stylesheet" inline>'))
        .pipe(gulp.dest('./build'));
})

gulp.task('processHtml', () => {
    return gulp
        .src('./build/*.html')
        .pipe(replace('<head>', phpHeader))
        .pipe(replace('</head>', ''))
        // .pipe(replace('<body>', ''))
        .pipe(replace('EVENT_ID', dataEventId))
        // .pipe(replace('</body>', ''))
        .pipe(gulp.dest('./build'));

})

gulp.task('processInline', () => {
    return gulp
        .src('./build/*.html')
        .pipe(
            inlinesource({
                compress: false,
                ignore: ['png'],
            })
        )
        .pipe(gulp.dest('./build'));
})

gulp.task('saveTo', () => {
    return gulp
        .src('./build/*.html')
        .pipe(rename({basename: 'block-template', extname: '.phtml'})) // Change the extension to .phtml
        .pipe(gulp.dest('../../view/adminhtml/templates/BlockTemplates'));
});


gulp.task('default', gulp.series(
        'replaceJs',
        'removeCss',
        'replaceCss',
        'processHtml',
        'processInline',
        'saveTo'
    )
);
