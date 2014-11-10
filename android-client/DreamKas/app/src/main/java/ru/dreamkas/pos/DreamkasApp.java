package ru.dreamkas.pos;

import android.app.Application;
import android.content.Context;

import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;

public class DreamkasApp extends Application {

    private static Context mContext;
    private static DecimalFormat mMoneyDecimalFormat;

    @Override
    public void onCreate() {
        super.onCreate();
        mContext = this;

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
