# flip5htmltopdf

## Installation
```
git clone https://github.com/Davidoc26/flip5htmltopdf.git
```

Install dependencies with ```composer i```

## Usage
```
php application.php parse <url> [filename] [path]
```
Use -f to specify output PDF filename.

Use -r to specify an absolute path to the PDF output directory (default: ./output)

### Using as package

First you need to install the package
```
composer require davidoc26/flip5htmltopdf
```

Then, when initializing the parser, pass a generator to it (you can also create your own generator)

```php
$parser = new Parser(
    url: $url,
    generator: new PdfGenerator($outputPath, $outputFilename)
);
$parser->fetchBook();
```

**Be careful! The pdf file creation process can reach memory_limit!**

