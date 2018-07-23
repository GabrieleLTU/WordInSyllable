# WordInSyllable
Program that automatically hyphenates English words into syllables. Developed with PHP

Info about REST API using:
Url: http://application.local/<table_name>/<other_info>

Get/Delete all data of table: http://application.local/<table_name>/

--- word table:
Get all words: http://application.local/word/
Get one word: http://application.local/word/<word_id OR word>

Update word: http://application.local/word/<word_id OR word>
+json (one word data): {"colums_name":"value"}

Insert word(s): http://application.local/word/
+json (can be insert many words): [{"column":"value}, {"column":"value"}]


Delete all words: http://application.local/word/
Delete one word: http://application.local/word/<word_id OR word>


--- syllable table:
The same as word, just table name syllable and syllable id/syllable

--- syllablebyword table:
Get all syllablebywords: http://application.local/syllablebyword/
Get one/some syllablebyword:
http://application.local/syllablebyword/<word_id OR word OR nothing>/<syllable_id OR syllable OR nothing>

Update syllablebyword:
 http://application.local/syllablebyword/<word_id OR nothing>/<syllable_id OR nothing>
+json (one syllablebyword data): {"colums_name":"value"}

Insert syllablebyword(s): http://application.local/syllablebyword/
+json (can be insert many syllablebyword): [{"column":"value}, {"column":"value"}]


Delete all syllablebyword: http://application.local/syllablebyword/
Delete one syllablebyword: http://application.local/syllablebyword/<word_id OR word>
