# flip5htmltopdf

## Installation
```
git clone https://github.com/Davidoc26/flip5htmltopdf.git
```

Install dependencies with ```composer i```

## Usage
```
php application.php parse <url> [filename] [temp]
```
Use -f to specify output PDF filename.

Use -t to store temp files(contains .jpg).

PDF file will be created in /output directory.

### Using as package

Firstly you should define your output and temporary directories

Then pass the full path to outputPath and tempPath arguments:
```php
    $flip5html = new Flip5Html(
        url: 'link to book',
        outputPath: __DIR__ . '/output',
        outputFilename: 'book',
        tempPath: __DIR__ . '/temp',
        dropTemp: false // Should the temporary directory be cleaned up
        );
```

**Be careful! The pdf file creation process can reach memory_limit!**

