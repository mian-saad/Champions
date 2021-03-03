import mysql
import mysql.connector
import nltk
import pandas
import sys
import sklearn
import numpy
import re
import string
mydb = mysql.connector.connect(
    host = "localhost",
    port = "8889",
    user = "root",
    password = "root",
    database = "champions"
)
mycursor = mydb.cursor()
mycursor.execute("select flp_id from wp_alert")
result = mycursor.fetchall()
mycursor = mydb.cursor()
mycursor.execute("select flp_skills from wp_arena")
result_arena = mycursor.fetchall()
skills = []
for i in result_arena:
    for j in i:
        if(j != ''):
            skills.append(j)
print(skills)
mycursor = mydb.cursor()
mycursor.execute("select alert_description from wp_alert")
result = mycursor.fetchall()
description = []
for i in result:
    for j in i:
        description.append(j)
import math
from creme import compose
from creme import feature_extraction
from creme import naive_bayes
docs = [(skills[0], 'Good'),(skills[1], 'bad')]
model = compose.Pipeline(('tokenize', feature_extraction.BagOfWords(lowercase=False)),
                         ('nb', naive_bayes.MultinomialNB(alpha=1)))
for sentence , label in docs:
    model = model.fit_one(sentence, label)
new_unseen_text = 'Professors abc Police'
print(model.predict_one(new_unseen_text))
