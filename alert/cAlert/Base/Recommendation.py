import mysql
# import mysql.connector
# import nltk
# import pandas
# import sys
# import sklearn
# import numpy
# import re
# import string
# import math
# import creme
# from creme import compose
# from creme import feature_extraction
# from creme import naive_bayes
# from creme import metrics
import sys
print(sys.path)

# unseen_text = sys.argv[1]
#
# mydb = mysql.connector.connect(
#     host = "localhost",
#     port = "8889",
#     user = "root",
#     password = "root",
#     database = "champions"
# )
# mycursor = mydb.cursor()
# mycursor.execute("SELECT wp_recommendationData.recommendation_data, wp_alert.alert_category FROM wp_recommendationData INNER JOIN wp_alert ON wp_recommendationData.alert_id = wp_alert.alert_id;")
# result = mycursor.fetchall()
# # print(result[1][0])
#
# label = []
# sentence = []
# x = 1
# for i in result:
#     for j in i:
#         if x==1:
#             label.append(j)
#             x = 0
#         else:
#             sentence.append(j)
#             x = 1
# docs = []
# if len(label) == len(sentence):
#     for y in range(len(label)):
#         docs.append((sentence[y], label[y]))
#         model = compose.Pipeline(('tokenize', feature_extraction.TFIDF(lowercase=False)), ('nb', naive_bayes.MultinomialNB(alpha=1)))
#
# new_unseen_text = 'damage'
# print('Before training: ',model.predict_one(unseen_text), '\n' )
#
# for sentence , label in docs:
#     model = model.fit_one(sentence, label)
#
# pr = model.predict_one(unseen_text)
# print(pr)