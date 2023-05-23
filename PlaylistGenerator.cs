using System.Net;

class Program
{
    static void Main()
    {
        // User input for cookie and authorization tokens
        Console.Write("Enter cookie value: ");
        string cookie = Console.ReadLine();

        Console.Write("Enter authorization token: ");
        string authToken = Console.ReadLine();

        // Create the request
        HttpWebRequest request = (HttpWebRequest)WebRequest.Create("https://chat.openai.com/backend-api/conversation");
        request.Method = "POST";
        request.Headers.Add("Authorization", $"Bearer {authToken}");
        request.ContentType = "application/json";
        request.Headers.Add("Cookie", cookie);

        // Set the request body
        string requestBody = "{\"action\":\"next\",\"messages\":[{\"id\":\"aaa2037e-a697-4d96-9c39-0512e81d4167\",\"author\":{\"role\":\"user\"},\"content\":{\"content_type\":\"text\",\"parts\":[\"\"]}}],\"parent_message_id\":\"aaa12b20-5cfd-4c23-bbf7-9de1e90fa984\",\"model\":\"text-davinci-002-render-sha\",\"timezone_offset_min\":-600,\"history_and_training_disabled\":false}"; 
        byte[] requestBodyBytes = System.Text.Encoding.UTF8.GetBytes(requestBody);

        request.ContentLength = requestBodyBytes.Length;

        using (Stream requestStream = request.GetRequestStream())
        {
            requestStream.Write(requestBodyBytes, 0, requestBodyBytes.Length);
        }

        // Send the request and get the response
        using (HttpWebResponse response = (HttpWebResponse)request.GetResponse())
        {
            using (StreamReader reader = new StreamReader(response.GetResponseStream()))
            {
                string responseText = reader.ReadToEnd();
                Console.WriteLine(responseText);
            }
        }

        Console.ReadLine();
    }
}
