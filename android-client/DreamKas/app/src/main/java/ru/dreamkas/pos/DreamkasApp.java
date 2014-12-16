package ru.dreamkas.pos;

import android.app.Application;
import android.content.Context;

import org.apache.commons.lang3.math.Fraction;

import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;

public class DreamkasApp extends Application {

    private static Context mContext;
    private static DecimalFormat mMoneyDecimalFormat;
    private static float mMakeupSquareSide;
    private static SupportedRatio mRatio;
    static float mScale; //getResources().getDisplayMetrics().density;

    public static int getDpValueInPixels(float value) {
        return (int) (value * mScale + 0.5f);
    }

    public static int toDkp(int widthSize) {
        return Math.round(widthSize*DreamkasApp.getDkp());
    }

    public static int toDkpInPixels(int value) {
        //(value * mScale + 0.5f)
        //value*DreamkasApp.getDkp()
        return DreamkasApp.getDpValueInPixels(DreamkasApp.toDkp(value));
    }

    public enum SupportedRatio{_16_10, _3_4};

    public static void setRatio(Fraction ratio, float width) {
        if(ratio.getDenominator() == 5 && ratio.getNumerator() == 8){
            DreamkasApp.mRatio = SupportedRatio._16_10;
            mMakeupSquareSide = width/16;
        }else if(ratio.equals("4/3")){
            DreamkasApp.mRatio = SupportedRatio._3_4;
            mMakeupSquareSide = width/4;
        }else {
            DreamkasApp.mRatio = SupportedRatio._16_10;
        }
    }

    public static float getDkp(){
        return DreamkasApp.getSquareSide() / 64;
    }

    public static float getSquareSide() {
        if(mMakeupSquareSide == 0){
            throw new IllegalStateException("SquareSide not initialized yet");
        }
        return mMakeupSquareSide;
    }

    public static SupportedRatio getRatio() {
        return mRatio;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        mContext = this;

        mScale = mContext.getResources().getDisplayMetrics().density;



        DecimalFormatSymbols otherSymbols = new DecimalFormatSymbols();
        otherSymbols.setDecimalSeparator(Constants.COMMA);
        mMoneyDecimalFormat = new DecimalFormat("0", otherSymbols);
        mMoneyDecimalFormat.setParseBigDecimal(true);
        mMoneyDecimalFormat.setMinimumFractionDigits(Constants.MINIMUM_FRACTION_DIGITS_MONEY);
        mMoneyDecimalFormat.setMaximumFractionDigits(Constants.MAXIMUM_FRACTION_DIGITS_MONEY);
    }

    public static Context getContext(){

        return mContext;
    }

    public static DecimalFormat getMoneyFormat(){
        return mMoneyDecimalFormat;
    }

    public static String getResourceString(Integer id){
        return mContext.getResources().getString(id);
    }
}
