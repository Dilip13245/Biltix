<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <style type="text/css">
        :root {
            --primary-color: #2c7be5;
            --secondary-color: #05AA6D;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --border-color: #dee2e6;
        }

        ::selection {
            background-color: var(--primary-color);
            color: white;
        }

        ::-moz-selection {
            background-color: var(--primary-color);
            color: white;
        }

        body {
            background-color: var(--light-gray);
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            line-height: 1.6;
        }

        .container-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        h1,
        h2,
        h3 {
            color: var(--secondary-color);
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 20px 0;
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--secondary-color);
            margin: 10px auto;
            border-radius: 2px;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 0;
        }

        code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            background-color: #f8f9fa;
            border: 1px solid var(--border-color);
            color: #e83e8c;
            display: block;
            margin: 14px 0;
            padding: 12px 15px;
            border-radius: 4px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        #container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .btn {
            background-color: var(--secondary-color);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #048a5a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 15px;
            resize: vertical;
            min-height: 200px;
            transition: border 0.3s ease;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(5, 170, 109, 0.2);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            align-items: center;
        }

        .radio-option {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        input[type="radio"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            accent-color: var(--secondary-color);
            cursor: pointer;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
        }

        .output-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid var(--secondary-color);
        }

        .copy-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        .copy-btn:hover {
            background-color: #1a68d1;
        }

        .output-content {
            white-space: pre-wrap;
            word-wrap: break-word;
            padding: 15px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: monospace;
            margin-bottom: 20px;
        }

        .json-viewer {
            background: #282c34;
            color: #abb2bf;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            overflow-x: auto;
            white-space: pre;
        }

        .json-key {
            color: #e06c75;
        }

        .json-value {
            color: #98c379;
        }

        .json-string {
            color: #98c379;
        }

        .json-number {
            color: #d19a66;
        }

        .json-boolean {
            color: #56b6c2;
        }

        .json-null {
            color: #56b6c2;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: var(--dark-gray);
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: var(--secondary-color);
            color: white;
            padding: 15px 25px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateX(200%);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateX(0);
        }

        .json-viewer-container {
            background: #1e1e1e;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            overflow: auto;
            max-height: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .json-viewer-controls {
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
        }

        .json-viewer-btn {
            background: #333;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.2s;
        }

        .json-viewer-btn:hover {
            background: #444;
        }

        .json-viewer {
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            color: #d4d4d4;
        }

        .json-viewer .line {
            display: flex;
            min-height: 20px;
        }

        .json-viewer .line:hover {
            background: rgba(90, 93, 94, 0.3);
        }

        .json-viewer .line-number {
            color: #858585;
            padding-right: 15px;
            text-align: right;
            min-width: 40px;
            user-select: none;
        }

        .json-viewer .line-content {
            flex-grow: 1;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .json-viewer .indent {
            display: inline-block;
            width: 20px;
        }

        .json-viewer .toggle {
            cursor: pointer;
            color: #569cd6;
            margin-right: 5px;
            user-select: none;
        }

        .json-viewer .key {
            color: #9cdcfe;
        }

        .json-viewer .string {
            color: #ce9178;
        }

        .json-viewer .number {
            color: #b5cea8;
        }

        .json-viewer .boolean {
            color: #569cd6;
        }

        .json-viewer .null {
            color: #569cd6;
        }

        .json-viewer .collapsed {
            color: #6a9955;
            font-style: italic;
            cursor: pointer;
        }

        .json-viewer .collapsed:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container-wrapper {
                margin: 20px auto;
                padding: 0 15px;
            }

            #container {
                padding: 20px;
            }

            h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-wrapper">
        <div id="container">
            <h1>Constructoin Encryption & Decryption Tool</h1>

            <div id="body">
                <form action="{{ route('web.enc-dec-data') }}" method="post">
                    {{ csrf_field() }}
                    <label for="data"><b>Enter Text to Encrypt/Decrypt:</b></label><br>
                    <textarea name="data" id="data" required placeholder="Enter your text or encrypted data here..." cols="30"
                        rows="10"></textarea>

                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" value="encrypt" name="type" id="encrypt" checked>
                            <label for="encrypt">Encrypt</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" value="decrypt" name="type" id="decrypt">
                            <label for="decrypt">Decrypt</label>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn">
                        <span id="submit-text">Process Data</span>
                    </button>
                </form>

                @if (isset($encrypt_value) || isset($decrypt_value))
                    <div class="output-container">
                        <h2>Result:</h2>

                        @if (isset($encrypt_value))
                            <button class="copy-btn" onclick="copyToClipboard('encrypt-output')">Copy Encrypted
                                Data</button>
                            <div id="encrypt-output" class="output-content">{{ $encrypt_value }}</div>
                        @endif

                        @if (isset($decrypt_value))
                            <button class="copy-btn" onclick="copyToClipboard('decrypt-output')">Copy Decrypted
                                Data</button>
                            <div id="decrypt-output" class="output-content">{{ $decrypt_value }}</div>

                            <h3 style="margin-top: 20px;">JSON View:</h3>
                            <div class="json-viewer-container">
                                <div class="json-viewer-controls">
                                    <button class="json-viewer-btn" onclick="expandAll()">Expand All</button>
                                    <button class="json-viewer-btn" onclick="collapseAll()">Collapse All</button>
                                    <button class="json-viewer-btn" onclick="copyJson()">Copy JSON</button>
                                </div>
                                <div class="json-viewer" id="json-viewer"></div>
                            </div>
                        @endif
                    </div>
                @endif


            </div>
        </div>

        <div class="footer">
            Boloo Security Tools &copy; <?php echo date('Y'); ?> | Secure Data Processing
        </div>
    </div>

    <div id="notification" class="notification">Copied to clipboard!</div>

    <script type="text/javascript">
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const range = document.createRange();
            range.selectNode(element);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);

            try {
                const successful = document.execCommand('copy');
                showNotification(successful ? 'Copied to clipboard!' : 'Failed to copy');
            } catch (err) {
                showNotification('Error copying text');
            }

            window.getSelection().removeAllRanges();
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Check if a string is valid JSON
        function isValidJson(str) {
            try {
                JSON.parse(str);
                return true;
            } catch (e) {
                return false;
            }
        }

        // Format JSON with syntax highlighting
        function syntaxHighlight(json) {
            if (typeof json != 'string') {
                json = JSON.stringify(json, null, 2);
            }

            // Escape HTML special chars
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

            // Add syntax highlighting
            return json.replace(
                /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
                function(match) {
                    let cls = 'json-value';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'json-key';
                        } else {
                            cls = 'json-string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'json-boolean';
                    } else if (/null/.test(match)) {
                        cls = 'json-null';
                    } else if (/^-?\d+\.?\d*([eE][+\-]?\d+)?$/.test(match)) {
                        cls = 'json-number';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                }
            );
        }

        // VS Code-like JSON viewer implementation
        class JsonViewer {
            constructor(element, json) {
                this.element = element;
                this.json = json;
                this.lineCount = 1;
                this.init();
            }

            init() {
                this.element.innerHTML = '';
                this.lineCount = 1;
                this.parseValue(this.json, 0);
            }

            createLine(content, indentLevel) {
                const line = document.createElement('div');
                line.className = 'line';

                const lineNumber = document.createElement('span');
                lineNumber.className = 'line-number';
                lineNumber.textContent = this.lineCount++;

                const lineContent = document.createElement('span');
                lineContent.className = 'line-content';

                // Add indentation
                for (let i = 0; i < indentLevel; i++) {
                    lineContent.appendChild(this.createIndent());
                }

                lineContent.innerHTML += content;

                line.appendChild(lineNumber);
                line.appendChild(lineContent);
                this.element.appendChild(line);

                return line;
            }

            createIndent() {
                const indent = document.createElement('span');
                indent.className = 'indent';
                return indent;
            }

            parseValue(value, indentLevel, isProperty = false) {
                if (value === null) {
                    return this.createLine(`<span class="null">null</span>`, indentLevel);
                }

                switch (typeof value) {
                    case 'string':
                        return this.createLine(`<span class="string">"${this.escapeHtml(value)}"</span>`, indentLevel);
                    case 'number':
                        return this.createLine(`<span class="number">${value}</span>`, indentLevel);
                    case 'boolean':
                        return this.createLine(`<span class="boolean">${value}</span>`, indentLevel);
                    case 'object':
                        if (Array.isArray(value)) {
                            return this.parseArray(value, indentLevel, isProperty);
                        } else {
                            return this.parseObject(value, indentLevel, isProperty);
                        }
                }
            }

            parseObject(obj, indentLevel, isProperty = false) {
                const keys = Object.keys(obj);
                if (keys.length === 0) {
                    return this.createLine(`{}`, indentLevel);
                }

                const openLine = this.createLine(`{`, indentLevel);
                const closeLine = this.createLine(`}`, indentLevel);

                const toggle = document.createElement('span');
                toggle.className = 'toggle';
                toggle.textContent = '-';
                toggle.onclick = () => this.toggle(openLine, closeLine);
                openLine.querySelector('.line-content').prepend(toggle);

                for (let i = 0; i < keys.length; i++) {
                    const key = keys[i];
                    const line = this.createLine(`<span class="key">"${key}"</span>: `, indentLevel + 1);
                    this.parseValue(obj[key], indentLevel + 1, true);

                    if (i < keys.length - 1) {
                        this.createLine(`,`, indentLevel + 1);
                    }
                }

                return {
                    openLine,
                    closeLine
                };
            }

            parseArray(arr, indentLevel, isProperty = false) {
                if (arr.length === 0) {
                    return this.createLine(`[]`, indentLevel);
                }

                const openLine = this.createLine(`[`, indentLevel);
                const closeLine = this.createLine(`]`, indentLevel);

                const toggle = document.createElement('span');
                toggle.className = 'toggle';
                toggle.textContent = '-';
                toggle.onclick = () => this.toggle(openLine, closeLine);
                openLine.querySelector('.line-content').prepend(toggle);

                for (let i = 0; i < arr.length; i++) {
                    this.parseValue(arr[i], indentLevel + 1);

                    if (i < arr.length - 1) {
                        this.createLine(`,`, indentLevel + 1);
                    }
                }

                return {
                    openLine,
                    closeLine
                };
            }

            toggle(openLine, closeLine) {
                const toggle = openLine.querySelector('.toggle');
                let current = openLine.nextElementSibling;

                if (toggle.textContent === '+') {
                    toggle.textContent = '-';
                    while (current && current !== closeLine) {
                        current.style.display = 'flex';
                        current = current.nextElementSibling;
                    }
                } else {
                    toggle.textContent = '+';
                    while (current && current !== closeLine) {
                        current.style.display = 'none';
                        current = current.nextElementSibling;
                    }
                }
            }

            escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        }

        // Helper functions
        function expandAll() {
            document.querySelectorAll('.json-viewer .toggle').forEach(toggle => {
                if (toggle.textContent === '+') {
                    toggle.click();
                }
            });
        }

        function collapseAll() {
            document.querySelectorAll('.json-viewer .toggle').forEach(toggle => {
                if (toggle.textContent === '-') {
                    toggle.click();
                }
            });
        }

        function copyJson() {
            const jsonText = document.getElementById('decrypt-output').textContent;
            navigator.clipboard.writeText(jsonText).then(() => {
                showNotification('JSON copied to clipboard!');
            });
        }

        // Initialize JSON viewer when page loads
        window.onload = function() {
            document.getElementById('data').focus();

            @if (isset($decrypt_value))
                const jsonViewerElement = document.getElementById('json-viewer');
                try {
                    const jsonData = JSON.parse(@json($decrypt_value));
                    new JsonViewer(jsonViewerElement, jsonData);
                } catch (e) {
                    try {
                        const unescaped = @json($decrypt_value).replace(/\\"/g, '"');
                        const jsonData = JSON.parse(unescaped);
                        new JsonViewer(jsonViewerElement, jsonData);
                    } catch (e2) {
                        jsonViewerElement.innerHTML = `
                    <div class="line">
                        <span class="line-number">1</span>
                        <span class="line-content">
                            <span class="string">"${@json($decrypt_value)}"</span>
                            <span style="color: #6A9955;">// Not valid JSON - displaying as string</span>
                        </span>
                    </div>
                `;
                    }
                }
            @endif
        };
    </script>
</body>

</html>
