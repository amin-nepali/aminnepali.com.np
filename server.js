const express = require('express');
const cors = require('cors');
const axios = require('axios');
require('dotenv').config();

const app = express();
app.use(cors());
app.use(express.json());

const TOGETHER_API_KEY = process.env.TOGETHER_API_KEY;

app.post('/chat', async (req, res) => {
  try {
    const userMessage = req.body.message;

    const response = await axios.post('https://api.together.xyz/v1/chat/completions', {
      model: "mistralai/Mixtral-8x7B-Instruct-v0.1",
      messages: [{ role: "user", content: userMessage }]
    }, {
      headers: {
        'Authorization': `Bearer ${TOGETHER_API_KEY}`,
        'Content-Type': 'application/json'
      }
    });

    const botReply = response.data.choices[0].message.content;
    res.json({ reply: botReply });
  } catch (err) {
    console.error(err.response?.data || err.message);
    res.status(500).json({ reply: "Error processing your request." });
  }
});

app.listen(3000, () => console.log("✅ Server running on port 3000"));
