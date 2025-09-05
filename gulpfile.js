import path from 'path';
import fs from 'fs';
import { glob } from 'glob';
import { src, dest, watch, series } from 'gulp';
import * as dartSass from 'sass'; // ⚡ Import moderno
import gulpSass from 'gulp-sass';
import terser from 'gulp-terser';
import sharp from 'sharp';

const sass = gulpSass(dartSass); // ⚡ Usando nueva API

const paths = {
  scss: 'src/scss/**/*.scss',
  js: 'src/js/**/*.js',
  img: 'src/img/**/*.{png,jpg,jpeg,svg}',
};

// CSS
export function css(done) {
  src(paths.scss, { sourcemaps: true })
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(dest('./public/build/css', { sourcemaps: '.' }));
  done();
}

// JS
export function js(done) {
  src(paths.js).pipe(terser()).pipe(dest('./public/build/js'));
  done();
}

// Imágenes
export async function imagenes(done) {
  const srcDir = './src/img';
  const buildDir = './public/build/img';
  const images = await glob(paths.img);

  await Promise.all(
    images.map((file) => procesarImagenes(file, srcDir, buildDir))
  );

  done();
}

async function procesarImagenes(file, srcDir, buildDir) {
  const relativePath = path.relative(srcDir, path.dirname(file));
  const outputSubDir = path.join(buildDir, relativePath);

  if (!fs.existsSync(outputSubDir)) {
    fs.mkdirSync(outputSubDir, { recursive: true });
  }

  const baseName = path.basename(file, path.extname(file));
  const extName = path.extname(file).toLowerCase();
  const outputFile = path.join(outputSubDir, `${baseName}${extName}`);

  if (extName === '.svg') {
    fs.copyFileSync(file, outputFile);
  } else {
    const options = { quality: 80 };
    await sharp(file).jpeg(options).toFile(outputFile);
    await sharp(file)
      .webp(options)
      .toFile(path.join(outputSubDir, `${baseName}.webp`));
    await sharp(file)
      .avif(options)
      .toFile(path.join(outputSubDir, `${baseName}.avif`));
  }
}

// Watcher
export function dev() {
  watch(paths.scss, css);
  watch(paths.js, js);
  watch(paths.img, imagenes);
}

// Default
export default series(js, css, imagenes, dev);
