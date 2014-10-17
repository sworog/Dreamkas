package ru.dreamkas.pos.controller;

import ru.dreamkas.pos.model.DrawerMenu;

public interface RestFragmentContainer{
    void onFragmentChange(DrawerMenu.AppStates target);
}
