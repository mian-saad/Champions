import mysql
import mysql.connector
import nltk
import pandas
import sys
import sklearn
import numpy
import re
import string
import math
import creme
from creme import compose
from creme import feature_extraction
from creme import naive_bayes
from creme import linear_model
from creme import preprocessing
from creme import metrics
from pprint import pprint

unseen_text = sys.argv[1]

mydb = mysql.connector.connect(
    host = "localhost",
    port = "8889",
    user = "root",
    password = "root",
    database = "champions"
)
mycursor = mydb.cursor()
mycursor.execute("SELECT wp_alert.alert_description, wp_arena.flp_skills FROM wp_arena INNER JOIN wp_alert ON wp_arena.flp_id = wp_alert.flp_id;")
result = mycursor.fetchall()

label = []
sentence = []
x = 1
for i in result:
    for j in i:
        if x==1:
            sentence.append(j)
            x = 0
        else:
            label.append(j)
            x = 1
docs = []
if len(label) == len(sentence):
    for y in range(len(label)):
        docs.append((sentence[y], label[y]))

model = compose.Pipeline(
    ('tokenize', feature_extraction.BagOfWords(lowercase=False)),
    ('nb', naive_bayes.MultinomialNB(alpha=1))
)

for sentence , label in docs:
    model = model.fit_one(sentence, label)


metric = metrics.Accuracy()
print(metric)


for sentence, label in docs:
    y_pred = model.predict_one(sentence)
    metric = metric.update(label, y_pred)
    model = model.fit_one(sentence, label)

pr = model.predict_one(unseen_text)
print(pr)
print(metric)