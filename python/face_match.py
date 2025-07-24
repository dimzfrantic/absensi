import sys
import face_recognition
from PIL import Image
import numpy as np
import os

# Pastikan argumen diberikan
if len(sys.argv) < 2:
    print("Error: Mohon berikan path gambar sebagai argumen.")
    print("Contoh: python check_env.py path/ke/gambar.jpg")
    sys.exit(1)

image_path = sys.argv[1]

# Cek apakah file ada
if not os.path.exists(image_path):
    print(f"Error: File tidak ditemukan di '{image_path}'")
    sys.exit(1)

print("--- Memulai Diagnosis Lingkungan face_recognition ---")
print(f"Mencoba memuat gambar: {image_path}\n")

try:
    # Langkah 1: Coba muat dengan Pillow (yang digunakan face_recognition)
    print("Langkah 1: Memuat gambar dengan pustaka Pillow...")
    pil_image = Image.open(image_path)
    print(f"  - Sukses. Mode gambar: {pil_image.mode}")

    # Jika mode bukan RGB, konversi
    if pil_image.mode != 'RGB':
        print(f"  - Mode bukan RGB, mengkonversi dari '{pil_image.mode}' ke 'RGB'...")
        pil_image = pil_image.convert('RGB')
        print(f"  - Konversi sukses. Mode baru: {pil_image.mode}")

    # Langkah 2: Konversi ke NumPy array
    print("\nLangkah 2: Mengkonversi gambar ke NumPy array...")
    numpy_image = np.array(pil_image)
    print("  - Sukses.")
    print(f"  - Shape array: {numpy_image.shape}")
    print(f"  - Tipe data (dtype): {numpy_image.dtype}")

    # Cek apakah array sudah benar (3 dimensi, tipe uint8)
    if len(numpy_image.shape) != 3 or numpy_image.shape[2] != 3:
        raise ValueError("Bentuk array tidak sesuai dengan format RGB 3-channel.")
    if numpy_image.dtype != np.uint8:
        raise TypeError("Tipe data array bukan uint8.")
        
    print("\n  ==> Data gambar tampaknya sudah dalam format yang BENAR. <==\n")

    # Langkah 3: Coba jalankan fungsi face_recognition
    print("Langkah 3: Menjalankan fungsi inti `face_recognition.face_locations()`...")
    face_locations = face_recognition.face_locations(numpy_image)
    
    print("\n--- HASIL ---")
    print("✅ SUKSES! Lingkungan dan instalasi `dlib`/`face_recognition` Anda tampaknya berfungsi.")
    if face_locations:
        print(f"  - Berhasil menemukan {len(face_locations)} wajah pada gambar.")
    else:
        print("  - Tidak ada wajah yang terdeteksi pada gambar (ini bukan error).")

except Exception as e:
    print("\n--- HASIL ---")
    print(f"❌ GAGAL! Terjadi error pada Langkah 3 saat memanggil fungsi face_recognition.")
    print(f"  - Pesan Error: {e}")
    print("\n  ==> Ini sangat mengindikasikan masalah pada instalasi `dlib` atau `face_recognition`. <==\n")

