package ru.dreamkas.pos.model;

import java.util.EnumMap;

public class DrawerMenu{
    private static EnumMap<AppStates, String> stateMap = new EnumMap<AppStates, String>(AppStates.class);
    static{
        stateMap.put(AppStates.Kas, "Касса");
        stateMap.put(AppStates.Store, "Сменить магазин");
        stateMap.put(AppStates.Exit, "Выйти");
    }

    public static EnumMap<AppStates, String> getMenuItems(){
        return stateMap;
    }

    public enum AppStates{
        Kas(0), Store(1), Exit(2);

        private final int code;
        AppStates(int code) { this.code = code; }
        public int getValue() { return code; }

        public static AppStates fromValue(int value){
            for (AppStates val: AppStates.values()){
                if (val.getValue() == value){
                    return val;
                }
            }
            return null;
        }
    }
}
