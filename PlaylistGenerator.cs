using System;
using System.IO;
using System.Linq;
using System.Xml;
using Newtonsoft.Json;
using Formatting = Newtonsoft.Json.Formatting;

namespace AlbumTrackLister
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Please choose a folder:");

            // Prompt the user to choose a folder
            var folderPath = Console.ReadLine();

            if (!Directory.Exists(folderPath))
            {
                Console.WriteLine("Invalid folder path.");
                return;
            }

            // Get all subdirectories in the chosen folder
            var subDirectories = Directory.GetDirectories(folderPath);

            // Generate a JSON array where the subfolders are the album names and the tracks are the mp3 files within
            var albums = subDirectories.Select(subDir =>
            {
                var albumName = Path.GetFileName(subDir);
                var mp3Files = Directory.GetFiles(subDir, "*.mp3").Select(Path.GetFileNameWithoutExtension);

                return new
                {
                    album = albumName,
                    artwork = "cover.jpg",
                    tracks = mp3Files
                };
            }).ToArray();

            // Serialize the albums array to JSON and write it to the console
            var json = JsonConvert.SerializeObject(albums, Formatting.Indented);
            Console.WriteLine(json);
        }
    }
}
