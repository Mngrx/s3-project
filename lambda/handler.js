'use strict';

const AWS = require('aws-sdk');
const S3 = new AWS.S3();
const REK = new AWS.Rekognition();


module.exports.getCats = async ({ Records: records }, context) => {

  try {


    let count = records.length;

    for (let index = 0; index < count; index++) {
      const element = records[index];

      const { key } = element.s3.object;

      const params = {
        Image: {
          S3Object: {
            Bucket: process.env.bucket,
            Name: key
          },
        },
        MaxLabels: 10,
        MinConfidence: 50,
      }

      let isCat = false;

      console.log("Dados: ", params);

      const returnRek = await REK.detectLabels(params).promise();

      for (let j = 0; j < returnRek.Labels.length; j++) {
        const item = returnRek.Labels[j];
        console.log(`Label:      ${item.Name}`)
        console.log(`Confidence: ${item.Confidence}`)

        if (item.Name == "Cat" && item.Confidence > 90.0) {
          isCat = true;
        }
      }


      const keys = {
        Bucket: process.env.bucket,
        Key: key
      };

      console.log("É gato: ", isCat);

      if (!isCat) {
        await S3.deleteObject(keys).promise();
      }
    }

    return {
      statusCode: 200,
      body: {},
    }

  } catch (error) {
    return error;
  }

};
