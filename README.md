## quickassets

QuickAssets is a simple script in development with the goal of making it easier to generate cache-bustable URLs in PHP.

The goal is to create a simple framework for defining a server (or multiple servers) and automatically setting cache-friendly URLs. You should be handling the actual caching yourself, either with server-side Apache/Nginx settings or with a cache engine like Varnish (or both).

### NOTE

Going forward, **query strings will be the default `showMethod` for QuickAssets**. This will increase cross-server compatibility and ease-of-use. If you would like to use the previous default, set the `showMethod` to `qa_inline`, which will generate filenames in the form `name.CACHEBUSTINGSTRING.ext`.

## MIT License
Copyright © Chris Van Patten, <http://www.chrisvanpatten.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
