<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class TranslateToThaiNumbers
{
    /** เส้นทางที่เป็นเกียรติบัตร: แสดงเลขไทยตามแบบเอกสาร */
    private const CERTIFICATE_PATH = '~(?:^|/)(?:area/)?print(?:PDF2?|activity|centeractivity|jow2?(?:_showbg)?|jowdoc|reportjow(?:_showbg)?|test|student)(?:/|$)~i';

    private const THAI_DIGITS = ['๐' => '0', '๑' => '1', '๒' => '2', '๓' => '3', '๔' => '4', '๕' => '5', '๖' => '6', '๗' => '7', '๘' => '8', '๙' => '9'];

    /**
     * Handle an incoming request.
     * แปลงเลขอารบิคเป็นเลขไทยเฉพาะหน้าพิมพ์เกียรติบัตรเท่านั้น (ดู CERTIFICATE_PATH)
     * หน้าอื่นทั้งหมดแปลงเลขไทยเป็นเลขอารบิค (รวมเลขไทยที่อยู่ในข้อมูล เช่น ชื่อเครือข่าย "เมือง ๑")
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $isCertificate = (bool) preg_match(self::CERTIFICATE_PATH, $request->path());

        if (! $isCertificate) {
            // หน้าทั่วไป: เลขไทย -> เลขอารบิค (เฉพาะ HTML)
            if ($response instanceof Response && ! $response instanceof \Symfony\Component\HttpFoundation\StreamedResponse
                && ! $response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse
                && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
                $content = $response->getContent();
                if ($content !== false && preg_match('/[๐-๙]/u', $content)) {
                    $response->setContent(strtr($content, self::THAI_DIGITS));
                }
            }

            return $response;
        }

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
