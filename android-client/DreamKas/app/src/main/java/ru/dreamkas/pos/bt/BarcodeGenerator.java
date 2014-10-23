package ru.dreamkas.pos.bt;

import android.bluetooth.BluetoothAdapter;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.util.Log;

import com.google.common.base.Optional;
import com.google.zxing.BarcodeFormat;
import com.google.zxing.WriterException;
import com.google.zxing.common.BitMatrix;
import com.google.zxing.oned.Code128Writer;

public class BarcodeGenerator {
    private final static char FNC3 = 'ó';

    public static Bitmap generateDatalogicLnkCommandBarcode(){
        //example "LnkBAC38708AC0BB";
        return generateBarCode(FNC3 + "LnkB" + getBluetoothMacAddress());
    }

    private static String getBluetoothMacAddress() {
        BluetoothAdapter mBluetoothAdapter = BluetoothAdapter.getDefaultAdapter();

        // if device does not support Bluetooth
        if(mBluetoothAdapter==null){
            Log.d("aaa", "device does not support bluetooth");
            return null;
        }

        return mBluetoothAdapter.getAddress();
    }

    private static Bitmap generateBarCode(String data){
        Code128Writer c9 = new Code128Writer();

        Bitmap bitmap = null;
        try {

            BitMatrix bm = c9.encode(data, BarcodeFormat.CODE_128,1000, 350);

            bitmap = Bitmap.createBitmap(1000, 350, Bitmap.Config.ARGB_8888);

            for (int i = 0; i < 1000; i++) {
                for (int j = 0; j < 350; j++) {
                    bitmap.setPixel(i, j, bm.get(i, j) ? Color.BLACK : Color.WHITE);
                }
            }
        } catch (WriterException e) {
            e.printStackTrace();
        }
        return bitmap;
    }

}
