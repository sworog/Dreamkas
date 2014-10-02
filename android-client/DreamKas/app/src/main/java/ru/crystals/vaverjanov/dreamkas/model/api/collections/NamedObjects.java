package ru.crystals.vaverjanov.dreamkas.model.api.collections;

import java.util.ArrayList;
import java.util.Collection;

import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;

public class NamedObjects extends ArrayList<NamedObject>
{
    public NamedObjects()
    {
    }

    public NamedObjects(Collection<NamedObject> lst)
    {
        super(lst);
    }



}

