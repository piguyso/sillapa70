<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class TranslateToThaiNumbers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Process only successful HTML responses that contain a </body> tag
        if ($response instanceof Response) {
            $contentType = $response->headers->get('Content-Type');
            if (strpos($contentType, 'text/html') !== false) {
                $content = $response->getContent();
                
                $script = '
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function translateToThaiNumbers(node) {
            if (!node) return;
            if (node.nodeType === Node.TEXT_NODE) {
                node.nodeValue = node.nodeValue.replace(/[0-9]/g, function(ch) {
                    return String.fromCharCode(ch.charCodeAt(0) + 3616);
                });
            } else if (node.nodeType === Node.ELEMENT_NODE) {
                const skipTags = ["SCRIPT", "STYLE", "INPUT", "TEXTAREA", "SELECT", "OPTION"];
                if (!skipTags.includes(node.tagName)) {
                    for (let i = 0; i < node.childNodes.length; i++) {
                        translateToThaiNumbers(node.childNodes[i]);
                    }
                }
            }
        }
        
        // Initial translation of the page body
        translateToThaiNumbers(document.body);
        
        // Watch for dynamic mutations (e.g. DataTables, modals, AJAX content changes)
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes) {
                    mutation.addedNodes.forEach(function(node) {
                        translateToThaiNumbers(node);
                    });
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    });
</script>';
                
                $pos = strripos($content, '</body>');
                if ($pos !== false) {
                    $content = substr($content, 0, $pos) . $script . substr($content, $pos);
                    $response->setContent($content);
                }
            }
        }

        return $response;
    }
}
