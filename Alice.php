<?php
/**
 * @author  Jason Turpin <jasonaturpin@gmail.com>
 */
class Alice {
    public $startData        = [];
    public $endData          = [];
    public $sentenceTracking = [];

    /**
     * Constructor Method
     */
    public function __construct($str, $sentencesWanted = 50) {
        if (! is_string($str) || ! is_numeric($sentencesWanted)) {
            return false;
        }

        // Remove preface
        $str = substr($str, strpos($str, 'CHAPTER I'));

        // Clean format and remove punctuation
        $str = preg_replace("/(?:[^\w\d !?'.]|\s*chapter \w+\s*)+/i", ' ', $str);
        $str = preg_replace("/(' | ')/", ' ', $str);
        $str = preg_replace('/\s{3,}/', '  ', $str);
        $str = trim($str);

        // Capture all sentences
        preg_match_all('/(?P<sentences>[A-Z].+?[.!?])/', $str, $matches);

        // Nothing was parsed
        if (! isset($matches['sentences'])) {
            return;
        }

        // Process each sentence
        array_walk($matches['sentences'], [$this, 'parseSentence']);

        // Generate output
        $this->buildOutput($sentencesWanted);
    }

    public function parseSentence($sentence) {
        // Captures all words
        preg_match_all("/(?P<words>[\w']+)/", $sentence, $matches);

        // Exit if no words were parsed, or sentence is too small
        if (! isset($matches['words']) || count($matches['words']) < 3) {
            return;
        }

        // Process sentences
        $words         = $matches['words'];
        $maxIterations = count($words);
        foreach ($words as $key => $word) {
            // IF last iteration
            if ($key + 1 == $maxIterations) {
                $this->endData[] = $word;
            } else {
                // IF first iteration
                if ($key == 0) {
                    $this->startData[] = $word;
                }
                $this->sentenceTracking[$word][] = $words[$key + 1];
            }
        }
    }

    public function buildOutput($sentenceCount) {
        // Validate parameters
        if (! is_numeric($sentenceCount) || $sentenceCount < 0) {
            return false;
        }

        for ($count = 0; $count <= $sentenceCount; $count++) {
            $completed = false;
            $str       = $this->startData[mt_rand(0, count($this->startData) - 1)];
            $word      = $str;
            $wordCount = 0;
            $sentenceLength = mt_rand(4, 12);

            while ($completed == false) {
                if (! isset($this->sentenceTracking[$word])) {
                    break;
                }
                $word = $this->sentenceTracking[$word][mt_rand(0, count($this->sentenceTracking[$word]) - 1)];
                $str .= ' '.$word;
                $completed = $wordCount++ > $sentenceLength && in_array($word, $this->endData);
            }
            echo trim($str).'.  ';
        }
    }
}
